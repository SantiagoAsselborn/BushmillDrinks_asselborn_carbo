<?php

namespace App\Controllers;

use App\Models\Bebida_model;
use App\Models\Direccion_model;
use App\Models\Provincia_model;
use App\Models\Ciudad_model;
use App\Models\Ventas_model;
use App\Models\Detalle_ventas_model;
use App\Models\Detalle_envio_model;
use App\Models\Medio_pago_model;
use App\Libraries\Pagos\PagoFactory;

class Carrito_controller extends BaseController
{
    public function index()
    {
        $bebidaModel = new Bebida_model();
        $bebidas = $bebidaModel
            ->select('bebida.*, marca.nombre_marca')
            ->join('marca', 'bebida.id_marca = marca.id_marca')
            ->where('estado_bebida', 1)
            ->findAll();
        return $this->renderizarConNavbar('nueva_plantilla', [
            'productos' => $bebidas
        ]);
    }

    //Operacion ver_carrito()
    public function ver_carrito()
    {
        if (session('id_perfil') != 2) {
            return redirect()->to('/');
        }

        return $this->renderizarConNavbar('carrito');
    }

    //Operación agregar_carrito()
    public function agregar_carrito()
    {
        if (session('id_perfil') != 2) {
            return redirect()->to('/');
        }
        $cart = \Config\Services::cart();
        $bebidaModel = new Bebida_model();
        $id = $this->request->getPost('id');
        $bebida = $bebidaModel->find($id);
        if (!$bebida) {
            return redirect()->back()
                ->with('error_stock', 'Bebida no encontrada.');
        }
        if ($bebida['stock_bebida'] < 1) {
            return redirect()->back()
                ->with('error_stock', 'Sin stock disponible.');
        }
        $cart->insert([
            'id'    => $bebida['id_bebida'],
            'qty'   => 1,
            'price' => $bebida['precio_bebida'],
            'name'  => $bebida['nombre_bebida']
        ]);
        return redirect()->to('ver_carrito')
            ->with('mensaje_carrito', 'Bebida agregada correctamente.');
    }

    //Operación eliminar_item($rowid), donde rowid es el identificador único del item en el carrito (distinto de id_bebida)
    public function eliminar_item($rowid)
    {
        if (session('id_perfil') != 2) {
            return redirect()->to('/');
        }
        $cart = \Config\Services::cart();
        $cart->remove($rowid);
        return redirect()->to('ver_carrito');
    }

    //Operacion vaciar_carrito()
    public function vaciar_carrito()
    {
        if (session('id_perfil') != 2) {
            return redirect()->to('/');
        }
        $cart = \Config\Services::cart();
        $cart->destroy();
        return redirect()->to('ver_carrito');
    }

    //Operación ordenar_compra()
    public function ordenar_compra()
    {
        if (session('id_perfil') != 2) {
            return redirect()->to('/');
        }
        $ciudadModel = new Ciudad_model();
        $provinciaModel = new Provincia_model();
        $medioPagoModel = new Medio_pago_model();
        $ciudades = $ciudadModel
            ->select('ciudad.*, provincia.nombre_provincia')
            ->join('provincia', 'provincia.id_provincia = ciudad.id_provincia')
            ->orderBy('nombre_ciudad', 'ASC')
            ->findAll();
        $provincias = $provinciaModel
            ->orderBy('nombre_provincia', 'ASC')
            ->findAll();
        $medios_pago = $medioPagoModel
            ->where('estado_medio_pago', 1)
            ->orderBy('nombre_medio_pago', 'ASC')
            ->findAll();
        return $this->renderizarConNavbar('backend/confirmar_compra_form', [
            'ciudades' => $ciudades,
            'provincias' => $provincias,
            'medios_pago' => $medios_pago
        ]);
    }

    //Operación confirmar_compra()
    public function confirmar_compra()
    {
        if (session('id_perfil') != 2) {
            return redirect()->to('/');
        }
        $validation = \Config\Services::validation();
        $validation->setRules([
            'id_ciudad'     => 'required|is_natural_no_zero',
            'medio_pago' => 'required',
            'codigo_postal' => 'required|numeric|exact_length[4]',
            'calle'         => 'required|min_length[3]|max_length[100]',
            'altura'        => 'required|numeric'
        ]);
        if (!$this->validate($validation->getRules())) {
            dd(
                'FALLÓ VALIDACIÓN',
                $this->validator->getErrors(),
                $this->request->getPost()
            );
        }
        if (!$this->validate($validation->getRules())) {
            $ciudadModel = new Ciudad_model();
            $provinciaModel = new Provincia_model();
            $medioPagoModel = new Medio_pago_model();
            return $this->renderizarConNavbar('backend/confirmar_compra_form', [
                'validation' => $this->validator,
                'ciudades' => $ciudadModel->findAll(),
                'provincias' => $provinciaModel->findAll(),
                'medios_pago' => $medioPagoModel
                    ->where('estado_medio_pago', 1)
                    ->findAll()
            ]);
        }
        $data = [
            'id_usuario'    => session('id_usuario'),
            'id_ciudad'     => $this->request->getPost('id_ciudad'),
            'medio_pago' => $this->request->getPost('medio_pago'),
            'codigo_postal' => $this->request->getPost('codigo_postal'),
            'calle'         => $this->request->getPost('calle'),
            'altura'        => $this->request->getPost('altura'),
        ];
        session()->set('datos_envio', $data);
        return redirect()->to('guardar_venta');
    }

    //Operación guardar_venta()
    public function guardar_venta()
    {
        if (session('id_perfil') != 2) {
            return redirect()->to('/');
        }
        $cart = \Config\Services::cart();
        $ventaModel = new Ventas_model();
        $detalleVentaModel = new Detalle_ventas_model();
        $bebidaModel = new Bebida_model();
        $direccionModel = new Direccion_model();
        $detalleEnvioModel = new Detalle_envio_model();
        $ciudadModel = new Ciudad_model();
        $provinciaModel = new Provincia_model();
        $medioPagoModel = new Medio_pago_model();
        $cart1 = $cart->contents();
        if (empty($cart1)) {
            return redirect()->to('ver_carrito');
        }
        $datos_envio = session()->get('datos_envio');
        if (!$datos_envio) {
            return redirect()->to('ordenar_compra')
                ->with('error', 'Faltan los datos de envío.');
        }
        // VALIDAR MEDIO DE PAGO
        $medioPago = $medioPagoModel
            ->where('LOWER(nombre_medio_pago)', strtolower($datos_envio['medio_pago']))
            ->where('estado_medio_pago', 1)
            ->first();
        if (!$medioPago) {
            return redirect()->to('ordenar_compra')
                ->with('error', 'Medio de pago inválido.');
        }
        // CALCULAR TOTAL
        $totalVenta = 0;
        foreach ($cart1 as $item) {
            $totalVenta += ($item['price'] * $item['qty']);
        }
        // PROCESAR PAGO CON STRATEGY
        $estrategiaPago = \App\Libraries\Pagos\PagoFactory::crear($medioPago['nombre_medio_pago']);

        if (!$estrategiaPago->procesarPago($totalVenta)) {
            return redirect()->to('ordenar_compra')
                ->with('error', 'No se pudo procesar el pago.');
        }
        // PROCEDIMIENTO 3 - VALIDAR STOCK ANTES DE INSERTAR (sp_validar_stock_bebida)
        $db = \Config\Database::connect();
        foreach ($cart1 as $item) {
            try {
                $db->query(
                    "CALL sp_verificar_stock_bebida(?, ?)",
                    [
                        $item['id'],
                        $item['qty']
                    ]
                );
            } catch (\Exception $e) {
                return redirect()->to('ver_carrito')
                    ->with(
                        'error_stock',
                        'Stock insuficiente para ' . $item['name']
                    );
            }
        }
        // INSERTAR VENTA
        $id_venta = $ventaModel->insert([
            'id_usuario'    => session('id_usuario'),
            'id_medio_pago' => (int)$medioPago['id_medio_pago'],
            'fecha_venta'   => date('Y-m-d H:i:s'),
            'total_venta'   => $totalVenta
        ]);
        // INSERTAR DIRECCIÓN
        $id_direccion = $direccionModel->insert([
            'id_usuario'    => session('id_usuario'),
            'calle'         => $datos_envio['calle'],
            'altura'        => $datos_envio['altura'],
            'codigo_postal' => $datos_envio['codigo_postal'],
            'id_ciudad'     => $datos_envio['id_ciudad']
        ]);

        // INSERTAR DETALLE ENVÍO
        $detalleEnvioModel->insert([
            'id_venta'     => $id_venta,
            'id_direccion' => $id_direccion,
            'costo_envio'  => 0
        ]);
        // DETALLES + ACTUALIZAR STOCK
        foreach ($cart1 as $item) {
            $detalleVentaModel->insert([
                'id_venta'         => $id_venta,
                'id_bebida'        => $item['id'],
                'detalle_cantidad' => $item['qty'],
                'detalle_precio'   => $item['price']
            ]);
            $bebida = $bebidaModel->find($item['id']);
            $bebidaModel->update($item['id'], [
                'stock_bebida' => $bebida['stock_bebida'] - $item['qty']
            ]);
        }
        $ciudadData = $ciudadModel->find($datos_envio['id_ciudad']);
        $nombreCiudad = $ciudadData ? $ciudadData['nombre_ciudad'] : 'No especificada';
        $provinciaData = $ciudadData
            ? $provinciaModel->find($ciudadData['id_provincia'])
            : null;
        $nombreProvincia = $provinciaData ? $provinciaData['nombre_provincia'] : 'No especificada';
        $cart->destroy();
        session()->remove('datos_envio');
        return $this->renderizarConNavbar('backend/confirmacion_compra', [
            'usuario' => [
                'nombre_usuario'   => session('nombre_usuario'),
                'apellido_usuario' => session('apellido_usuario'),
                'email_usuario'    => session('email_usuario')
            ],
            'venta' => [
                'id_venta' => $id_venta,
                'nombre_medio_pago' => $medioPago['nombre_medio_pago']
            ],
            'envio' => $datos_envio,
            'nombre_provincia' => $nombreProvincia,
            'nombre_ciudad' => $nombreCiudad,
            'carrito' => $cart1,
            'total' => $totalVenta
        ]);
    }

    //Operacion listar_ventas()
    public function listar_ventas()
    {
        if (session('id_perfil') != 1) {
            return redirect()->to('/');
        }
        $ventaModel = new Ventas_model();
        $builder = $ventaModel
            ->select('
                venta.*,
                medio_pago.nombre_medio_pago,
                usuario.nombre_usuario,
                usuario.apellido_usuario,
                usuario.email_usuario,
                direccion.calle,
                direccion.altura,
                direccion.codigo_postal,
                ciudad.nombre_ciudad,
                provincia.nombre_provincia
            ')
            ->join('usuario', 'usuario.id_usuario = venta.id_usuario')
            ->join('medio_pago', 'medio_pago.id_medio_pago = venta.id_medio_pago', 'left')
            ->join('detalle_envio', 'detalle_envio.id_venta = venta.id_venta', 'left')
            ->join('direccion', 'direccion.id_direccion = detalle_envio.id_direccion', 'left')
            ->join('ciudad', 'ciudad.id_ciudad = direccion.id_ciudad', 'left')
            ->join('provincia', 'provincia.id_provincia = ciudad.id_provincia', 'left')
            ->orderBy('venta.fecha_venta', 'DESC');
        $ventas = $builder->findAll();
        $detalleVentaModel = new Detalle_ventas_model();
        $ventasAgrupadas = [];
        foreach ($ventas as $venta) {
            $detalles = $detalleVentaModel
                ->select('detalle_ventas.*, bebida.nombre_bebida')
                ->join('bebida', 'bebida.id_bebida = detalle_ventas.id_bebida')
                ->where('id_venta', $venta['id_venta'])
                ->findAll();
            $productosVenta = [];
            foreach ($detalles as $d) {
                $subtotal = $d['detalle_cantidad'] * $d['detalle_precio'];
                $productosVenta[] = [
                    'nombre'   => $d['nombre_bebida'],
                    'cantidad' => $d['detalle_cantidad'],
                    'precio'   => $d['detalle_precio'],
                    'subtotal' => $subtotal
                ];
            }
            $ventasAgrupadas[] = [
                'id_venta' => $venta['id_venta'],
                'fecha' => $venta['fecha_venta'],
                'cliente' => ($venta['nombre_usuario'] ?? '') . ' ' .
                    ($venta['apellido_usuario'] ?? ''),
                'email' => $venta['email_usuario'] ?? '-',
                'direccion' => ($venta['calle'] ?? '-') . ' ' .
                    ($venta['altura'] ?? '-') . ', ' .
                    ($venta['nombre_ciudad'] ?? '-') . ' (' .
                    ($venta['nombre_provincia'] ?? '-') . ')',
                'codigo_postal' =>
                $venta['codigo_postal'] ?? '-',
                'medio_pago' =>
                $venta['nombre_medio_pago'] ?? 'No especificado',
                'total' => $venta['total_venta'],
                'productos' => $productosVenta
            ];
        }
        return $this->renderizarConNavbar(
            'backend/listar_ventas',
            [
                'ventasAgrupadas' => $ventasAgrupadas
            ]
        );
    }

    //Operación actualizar_cantidad()
    public function actualizar_cantidad()
    {
        if (session('id_perfil') != 2) {
            return redirect()->to('/');
        }
        $cart = \Config\Services::cart();
        $rowid = $this->request->getPost('rowid');
        $accion = $this->request->getPost('accion');
        $item = $cart->getItem($rowid);
        if (!$item) {
            return redirect()->back();
        }
        $qty = $item['qty'];
        if ($accion == 'sumar') {
            $bebidaModel = new Bebida_model();
            $bebida = $bebidaModel->find($item['id']);
            if ($bebida && $qty < $bebida['stock_bebida']) {
                $qty++;
            } else {
                return redirect()->back()
                    ->with(
                        'error',
                        'No hay más stock disponible.'
                    );
            }
        }
        if ($accion == 'restar' && $qty > 1) {
            $qty--;
        }
        $cart->update([
            'rowid' => $rowid,
            'qty' => $qty
        ]);
        return redirect()->back();
    }

    //Operación historial_cliente()
    public function historial_cliente()
    {
        if (session('id_perfil') != 2) {
            return redirect()->to('/');
        }
        $ventaModel = new Ventas_model();
        $detalleVentaModel = new Detalle_ventas_model();
        $id_usuario = session('id_usuario');
        $ventas_raw = $ventaModel
            ->select('
                venta.*,
                medio_pago.nombre_medio_pago,
                direccion.calle,
                direccion.altura,
                direccion.codigo_postal,
                ciudad.nombre_ciudad,
                provincia.nombre_provincia
            ')
            ->join('medio_pago', 'medio_pago.id_medio_pago = venta.id_medio_pago', 'left')
            ->join('detalle_envio', 'detalle_envio.id_venta = venta.id_venta', 'left')
            ->join('direccion', 'direccion.id_direccion = detalle_envio.id_direccion', 'left')
            ->join('ciudad', 'ciudad.id_ciudad = direccion.id_ciudad', 'left')
            ->join('provincia', 'provincia.id_provincia = ciudad.id_provincia', 'left')
            ->where('venta.id_usuario', $id_usuario)
            ->orderBy('venta.fecha_venta', 'DESC')
            ->findAll();
        $ventas = [];
        foreach ($ventas_raw as $venta) {
            $detalles = $detalleVentaModel
                ->select('detalle_ventas.*, bebida.nombre_bebida')
                ->join('bebida', 'bebida.id_bebida = detalle_ventas.id_bebida')
                ->where('id_venta', $venta['id_venta'])
                ->findAll();
            $productos = [];
            $total_venta = 0;
            foreach ($detalles as $detalle) {
                $subtotal =
                    $detalle['detalle_cantidad'] *
                    $detalle['detalle_precio'];
                $total_venta += $subtotal;
                $productos[] = [
                    'nombre' => $detalle['nombre_bebida'],
                    'cantidad' => $detalle['detalle_cantidad'],
                    'precio_unitario' => $detalle['detalle_precio'],
                    'subtotal' => $subtotal
                ];
            }
            $ventas[] = [
                'id_venta' => $venta['id_venta'],
                'nombre_medio_pago' =>
                $venta['nombre_medio_pago'] ?? 'No especificado',
                'fecha' => $venta['fecha_venta'],
                'total' => $total_venta,
                'direccion_completa' => ($venta['calle'] ?? '-') . ' ' .
                    ($venta['altura'] ?? '-') . ', ' .
                    ($venta['nombre_ciudad'] ?? '-') . ' (' .
                    ($venta['nombre_provincia'] ?? '-') . ')',
                'codigo_postal' =>
                $venta['codigo_postal'] ?? '-',
                'productos' => $productos
            ];
        }
        return $this->renderizarConNavbar(
            'backend/listar_compras_cliente',
            ['ventas' => $ventas]
        );
    }
}
