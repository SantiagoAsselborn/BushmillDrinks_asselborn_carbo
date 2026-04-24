<?php

namespace App\Controllers;

use App\Models\Bebida_model;
use App\Models\Direccion_model;
use App\Models\Provincia_model;
use App\Models\Ciudad_model;
use App\Models\Categoria_model;
use App\Models\Ventas_model;
use App\Models\Detalle_ventas_model;
use App\Models\Detalle_envio_model;
use App\Models\Usuario_model;
use DateTime;

class Carrito_controller extends BaseController{

    public function index()
    {
        $bebidaModel = new Bebida_model();
        $bebidas = $bebidaModel
            ->select('bebida.*, marca.nombre_marca')
            ->join('marca', 'bebida.id_marca = marca.id_marca')
            ->where('estado_bebida', 1)
            ->findAll();
        return $this->renderizarConNavbar('nueva_plantilla', ['bebida' => $bebidas]);
    }

    // Ver carrito
    public function ver_carrito()
        {
        if (session('id_perfil') != 2) return redirect()->to('/');
        //navbar personalizado
        $navbar = 'layout/navbar'; // Navbar por defecto (visitante)
        if (session()->has('id_perfil')) {
            if (session('id_perfil') == 1) {
            $navbar = session('modo_cliente') ? 'layout/navbarAdminVisitante' : 'layout/navbarAdmin';
            } elseif (session('id_perfil') == 2) {
            $navbar = 'layout/navbarCliente';
        }
    }
        $cart = \Config\Services::cart();
        $data['titulo']= 'Carrito de Compras';
        return view($navbar).view('carrito').view('layout/footer');

    }

    // Agregar producto al carrito
    public function agregar_carrito()
{
    if (session('id_perfil') != 2) return redirect()->to('/');

    $cart = \Config\Services::cart();
    $request = \Config\Services::request();
    $bebidaModel = new \App\Models\Bebida_model();

    // Buscar la bebida
    $bebida = $bebidaModel->find($request->getPost('id'));

    // Verifica existencia y stock
    if (!$bebida || $bebida['stock_bebida'] < 1) {
        session()->setFlashdata('error_stock', 'Esta bebida no tiene stock disponible.');
        return redirect()->back();
    }
    // Prepara datos para el carrito
    $data = [
        'id'    => $bebida['id_bebida'],
        'name'  => $bebida['nombre_bebida'],
        'price' => $bebida['precio_bebida'],
        'qty'   => 1,
        'options' => [
            'precio_original' => $bebida['precio_bebida']
        ]
    ];

    $cart->insert($data);

    // Mensaje de éxito
    session()->setFlashdata('mensaje_carrito', '¡Bebida agregada al carrito!');

    return redirect()->route('ver_carrito');
}


    public function eliminar_item($rowid){
        if (session('id_perfil') != 2) return redirect()->to('/');
        $cart = \Config\Services::cart();
        $cart->remove($rowid);
        return redirect()->to('ver_carrito');
    }

    public function vaciar_carrito(){
        if (session('id_perfil') != 2) return redirect()->to('/');
        $cart = \Config\Services::cart();
        $cart->destroy();  
        return redirect()->to('ver_carrito'); 
    }

    public function ordenar_compra()
    {
    if (session('id_perfil') != 2) return redirect()->to('/');

    $ciudadModel = new Ciudad_model();
    $provinciaModel = new Provincia_model();
    $ciudades = $ciudadModel
        ->select('ciudad.*, provincia.nombre_provincia')
        ->join('provincia', 'provincia.id_provincia = ciudad.id_provincia')
        ->orderBy('nombre_ciudad', 'ASC')
        ->findAll();
    $provincias = $provinciaModel
        ->select('provincia.*')
        ->orderBy('nombre_provincia', 'ASC')
        ->findAll();

    $data = [
        'ciudades' => $ciudades,
        'provincias' => $provincias
    ];

    // 5. Renderizar usando tu método unificado
    return $this->renderizarConNavbar('backend/confirmar_compra_form', $data);
}

    public function confirmar_compra()
    {
    if (session('id_perfil') != 2) return redirect()->to('/');

    $validation = \Config\Services::validation();
    $validation->setRules([
        'id_ciudad'     => 'required|is_natural_no_zero',
        'codigo_postal' => 'required|numeric|exact_length[4]',
        'calle'         => 'required|min_length[3]|max_length[100]',
        'altura'        => 'required|numeric'
    ], [
        // Mensajes de error personalizados
        'id_ciudad' => ['required' => 'Debe seleccionar una ciudad.'],
        'codigo_postal' => [
            'required' => 'El código postal es obligatorio.',
            'numeric' => 'El código postal debe ser numérico.',
            'exact_length' => 'El código postal debe tener 4 dígitos.'
        ],
        'calle' => [
            'required' => 'La calle es obligatoria.',
            'min_length' => 'La dirección es demasiado corta.'
        ],
        'altura' => [
            'required' => 'El número o altura es obligatorio.',
            'numeric' => 'La altura debe ser un número.'
        ]
    ]);

    // 3. Ejecutar validación
    if (!$this->validate($validation->getRules())) {
        // Si falla, determinamos el navbar según el perfil para no romper la estética
        $navbar = 'layout/navbar'; 
        if (session()->has('id_perfil')) {
            if (session('id_perfil') == 1) {
                $navbar = session('modo_cliente') ? 'layout/navbarAdminVisitante' : 'layout/navbarAdmin';
            } elseif (session('id_perfil') == 2) {
                $navbar = 'layout/navbarCliente';
            }
        }
        return view($navbar)
             . view('backend/confirmar_compra_form', ['validation' => $this->validator])
             . view('layout/footer');
    }

    $data = [
        'id_usuario'    => session('id_usuario'), 
        'id_ciudad'     => $this->request->getPost('id_ciudad'),
        'codigo_postal' => $this->request->getPost('codigo_postal'),
        'calle'         => $this->request->getPost('calle'),
        'altura'        => $this->request->getPost('altura'),
    ];

    session()->set('datos_envio', $data);

    // 5. Redirigir a la función que hace los inserts en la base de datos
    return redirect()->to('guardar_venta');
    }
    
    public function guardar_venta()
{
    if (session('id_perfil') != 2) return redirect()->to('/');
    
    $cart = \Config\Services::cart();
    $ventaModel = new Ventas_model();
    $detalleVentaModel = new Detalle_ventas_model();
    $bebidaModel = new Bebida_model();
    $direccionModel = new Direccion_model();
    $detalleEnvioModel = new Detalle_envio_model();
    $ciudadModel = new Ciudad_model(); // Importante para buscar el nombre
    $provinciaModel = new Provincia_model(); // Importante para buscar el nombre

    $cart1 = $cart->contents();
    if (empty($cart1)) return redirect()->to('ver_carrito');

    // 1. Calcular el total del carrito antes de guardar
    $totalVenta = 0;
    foreach ($cart1 as $item) {
        $totalVenta += ($item['price'] * $item['qty']);
    }

    // 2. Validar Stock
    foreach ($cart1 as $item) {
        $bebida = $bebidaModel->find($item['id']);
        if ($bebida['stock_bebida'] < $item['qty']) {
            session()->setFlashdata('error_stock', 'La bebida "' . esc($bebida['nombre_bebida']) . '" no tiene suficiente stock.');
            return redirect()->route('ver_carrito');
        }
    }

    // 3. Insertar Venta
    $id_venta = $ventaModel->insert([
        'id_usuario'  => session('id_usuario'),
        'fecha_venta' => date('Y-m-d H:i:s'),
        'total_venta' => $totalVenta,
    ]);

    // 4. Guardar Direccion
    $datos_envio = session()->get('datos_envio');
    $id_direccion = $direccionModel->insert([
        'id_usuario'    => session('id_usuario'),
        'calle'         => $datos_envio['calle'],
        'altura'        => $datos_envio['altura'],
        'codigo_postal' => $datos_envio['codigo_postal'],
        'id_ciudad'     => $datos_envio['id_ciudad'],
    ]);

    // 5. Guardar Detalle Envio
    $detalleEnvioModel->insert([
        'id_venta'     => $id_venta,
        'id_direccion' => $id_direccion,
        'costo_envio'  => 0,
    ]);

    // 6. Procesar items y stock
    foreach ($cart1 as $item) {
        $detalleVentaModel->insert([
            'id_venta'         => $id_venta,
            'id_bebida'        => $item['id'],
            'detalle_cantidad' => $item['qty'],
            'detalle_precio'   => $item['price'],
        ]);

        $bebidaModel->update($item['id'], [
            'stock_bebida' => ($bebidaModel->find($item['id'])['stock_bebida'] - $item['qty'])
        ]);
    }

    // 7. Buscar el nombre de la ciudad y provincia para la vista
    $ciudadData = $ciudadModel->find($datos_envio['id_ciudad']);
    $nombreCiudad = $ciudadData ? $ciudadData['nombre_ciudad'] : 'No especificada';

    $provinciaData = $provinciaModel->find($ciudadData['id_provincia']);
    $nombreProvincia = $provinciaData ? $provinciaData['nombre_provincia'] : 'No especificada';
    // 8. Limpieza
    $cart->destroy();
    session()->remove('datos_envio');

    // 9. Retornar vista con todos los datos necesarios
    return $this->renderizarConNavbar('backend/confirmacion_compra', [
        'usuario' => [
            'nombre_usuario'   => session('nombre_usuario'),
            'apellido_usuario' => session('apellido_usuario'),
            'email_usuario'    => session('email_usuario')
        ],
        'envio'         => $datos_envio,
        'nombre_provincia' => $nombreProvincia,
        'nombre_ciudad' => $nombreCiudad, // Variable que faltaba
        'carrito'       => $cart1,
        'total'         => $totalVenta
    ]);
}
    
    public function listar_ventas()
{
    if (session('id_perfil') != 1) return redirect()->to('/');

    $ventaModel = new \App\Models\Ventas_model();
    
    // Obtener parámetros de fecha
    $fechaInicio = $this->request->getGet('fecha_inicio');
    $fechaFin = $this->request->getGet('fecha_fin');

    // 1. Construir consulta con JOINs
    $builder = $ventaModel->select('venta.*, usuario.nombre_usuario, usuario.apellido_usuario, usuario.email_usuario, 
                                    direccion.calle, direccion.altura, direccion.codigo_postal, 
                                    ciudad.nombre_ciudad, provincia.nombre_provincia')
        ->join('usuario', 'usuario.id_usuario = venta.id_usuario')
        ->join('detalle_envio', 'detalle_envio.id_venta = venta.id_venta', 'left')
        ->join('direccion', 'direccion.id_direccion = detalle_envio.id_direccion', 'left')
        ->join('ciudad', 'ciudad.id_ciudad = direccion.id_ciudad', 'left')
        ->join('provincia', 'provincia.id_provincia = ciudad.id_provincia', 'left');

    // 2. Aplicar filtros de fecha
    if (!empty($fechaInicio)) {
        $fI = \DateTime::createFromFormat('d/m/Y', $fechaInicio);
        if ($fI) $builder->where('DATE(venta.fecha_venta) >=', $fI->format('Y-m-d'));
    }

    if (!empty($fechaFin)) {
        $fF = \DateTime::createFromFormat('d/m/Y', $fechaFin);
        if ($fF) $builder->where('DATE(venta.fecha_venta) <=', $fF->format('Y-m-d'));
    }

    $ventas = $builder->orderBy('venta.fecha_venta', 'DESC')->findAll();

    // 3. Obtener detalles y estructurar datos
    $detalleVentaModel = new \App\Models\Detalle_ventas_model();
    $ventasAgrupadas = [];

    foreach ($ventas as $venta) {
        $detalles = $detalleVentaModel->select('detalle_ventas.*, bebida.nombre_bebida')
            ->join('bebida', 'bebida.id_bebida = detalle_ventas.id_bebida')
            ->where('id_venta', $venta['id_venta'])
            ->findAll();

        $totalVenta = 0;
        $productosVenta = [];
        foreach ($detalles as $d) {
            $subtotal = $d['detalle_cantidad'] * $d['detalle_precio'];
            $totalVenta += $subtotal;
            $productosVenta[] = [
                'nombre'   => $d['nombre_bebida'],
                'cantidad' => $d['detalle_cantidad'],
                'precio'   => $d['detalle_precio'],
                'subtotal' => $subtotal
            ];
        }

        // Manejo seguro de datos de dirección (si vienen nulos)
        $dir = ($venta['calle'] ?? '') . ' ' . ($venta['altura'] ?? '');
        $ubicacion = ($venta['nombre_ciudad'] ?? 'N/A') . ', ' . ($venta['nombre_provincia'] ?? 'N/A');

        $ventasAgrupadas[] = [
            'id_venta'      => $venta['id_venta'],
            'fecha'         => $venta['fecha_venta'],
            // Aseguramos que los campos de usuario existan
            'cliente'       => ($venta['nombre_usuario'] ?? 'Usuario') . ' ' . ($venta['apellido_usuario'] ?? ''),
            'email'         => $venta['email_usuario'] ?? 'No disponible',
            'direccion'     => trim($dir) . ' (' . $ubicacion . ')',
            'codigo_postal' => $venta['codigo_postal'] ?? '-',
            'total'         => $totalVenta,
            'productos'     => $productosVenta
        ];
    }

    // Usamos el renderizador que ya tienes configurado
    return $this->renderizarConNavbar('backend/listar_ventas', [
        'ventasAgrupadas' => $ventasAgrupadas,
        'fechaInicio'     => $fechaInicio,
        'fechaFin'        => $fechaFin
    ]);
}

    public function actualizar_cantidad()
    {   
        if (session('id_perfil') != 2) return redirect()->to('/');
    
        $cart = \Config\Services::cart();
        $rowid = $this->request->getPost('rowid');
        $accion = $this->request->getPost('accion');

        $item = $cart->getItem($rowid);
        if (!$item) {
            return redirect()->back()->with('error', 'Producto no encontrado en el carrito');
        }

        $nuevaCantidad = $item['qty'];

        if ($accion == 'sumar') {
            // 1. Consultar el stock real en la base de datos
            $bebidaModel = new Bebida_model();
            $bebida = $bebidaModel->find($item['id']); // El 'id' guardado en el cart

            if ($bebida && $nuevaCantidad < $bebida['stock_bebida']) {
                $nuevaCantidad += 1;
            } else {
                return redirect()->back()->with('error', 'No hay más stock disponible de ' . $bebida['nombre_bebida']);
            }
        }
 
        elseif ($accion == 'restar' && $item['qty'] > 1) {
            $nuevaCantidad -= 1;
        }

        // 2. Actualizar el carrito con la nueva cantidad validada
        $cart->update([
            'rowid' => $rowid,
            'qty'   => $nuevaCantidad
        ]);

        return redirect()->back();
    }

    public function historial_cliente()
    {
        if (session('id_perfil') != 2) return redirect()->to('/');

        $ventaModel = new \App\Models\Ventas_model();
        $detalleVentaModel = new \App\Models\Detalle_ventas_model();
        $bebidaModel = new \App\Models\Bebida_model();
    
        $id_usuario = session('id_usuario');

        // 1. Obtener ventas del cliente con JOIN a Detalle_Envio, Direccion, Ciudad y Provincia
        $ventas_raw = $ventaModel
            ->select('venta.*, direccion.calle, direccion.altura, direccion.codigo_postal, ciudad.nombre_ciudad, provincia.nombre_provincia')
            ->join('detalle_envio', 'detalle_envio.id_venta = venta.id_venta', 'left')
            ->join('direccion', 'direccion.id_direccion = detalle_envio.id_direccion', 'left')
            ->join('ciudad', 'ciudad.id_ciudad = direccion.id_ciudad', 'left')
            ->join('provincia', 'provincia.id_provincia = ciudad.id_provincia', 'left')
            ->where('venta.id_usuario', $id_usuario) // Ajustado a 'id_usuario' según tu DB
            ->orderBy('venta.fecha_venta', 'DESC')
            ->findAll();

        $ventas = [];

        foreach ($ventas_raw as $venta) {
            // 2. Obtener productos de esta venta con JOIN a bebidas
            $detalles = $detalleVentaModel
                ->select('detalle_ventas.*, bebida.nombre_bebida')
                ->join('bebida', 'bebida.id_bebida = detalle_ventas.id_bebida')
                ->where('id_venta', $venta['id_venta'])
                ->findAll();

            $productos = [];
            $total_venta = 0;

            foreach ($detalles as $detalle) {
                $subtotal = $detalle['detalle_cantidad'] * $detalle['detalle_precio'];
                $total_venta += $subtotal;

                $productos[] = [
                    'nombre' => $detalle['nombre_bebida'],
                    'cantidad' => $detalle['detalle_cantidad'],
                    'precio_unitario' => $detalle['detalle_precio'],
                    'subtotal' => $subtotal
                ];
            }

            // 3. Estructurar datos para la vista
            $ventas[] = [
                'id_venta' => $venta['id_venta'],
                'fecha'    => $venta['fecha_venta'],
                'total'    => $total_venta,
                // Construimos la dirección completa dinámicamente
                'direccion_completa' => $venta['calle'] . ' ' . $venta['altura'] . ', ' . $venta['nombre_ciudad'] . ' (' . $venta['nombre_provincia'] . ')',
                'codigo_postal' => $venta['codigo_postal'] ?? '-',
                'productos' => $productos
            ];
        }

        return $this->renderizarConNavbar('backend/listar_compras_cliente', ['ventas' => $ventas]);
    }

}
