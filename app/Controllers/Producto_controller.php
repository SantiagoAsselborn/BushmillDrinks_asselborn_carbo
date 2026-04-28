<?php

namespace App\Controllers;

use App\Models\Bebida_model;
use App\Models\Marca_model;
use App\Models\Categoria_model;

class Producto_controller extends BaseController
{

    public function index()
    {
        $bebidaModel = new \App\Models\Bebida_model();
        $bebidas = $bebidaModel
            ->select('bebida.*, marca.nombre_marca')
            ->join('marca', 'bebida.id_marca = marca.id_marca')
            ->where('bebida_estado', 1)
            ->findAll();
        $this->renderizarConNavbar('nueva_plantilla', ['bebida' => $bebidas]);
    }

    public function catalogo()
    {
        $bebidaModel = new Bebida_model();

        $marca = $this->request->getGet('marca');
        $categoria = $this->request->getGet('categoria');
        $precio_max = $this->request->getGet('precio_max');

        $builder = $bebidaModel->builder()
            ->select('bebida.*, marca.nombre_marca, categoria.nombre_categoria')
            ->join('marca', 'bebida.id_marca = marca.id_marca')
            ->join('categoria', 'bebida.id_categoria = categoria.id_categoria')
            ->orderBy('categoria.nombre_categoria', 'ASC')
            ->orderBy('bebida.nombre_bebida', 'ASC')
            ->where('bebida.estado_bebida', 1);

        if (!empty($marca)) {
            $builder->like('marca.nombre_marca', $marca);
        }

        if (!empty($categoria)) {
            $builder->like('categoria.nombre_categoria', $categoria);
        }

        if (!empty($precio_max)) {
            $builder->where('bebida.precio_bebida <=', $precio_max);
        }

        $data['bebida'] = $builder->get()->getResultArray();
        $data['marca'] = (new Marca_model())->findAll();
        $data['categoria'] = (new Categoria_model())->orderBy('nombre_categoria', 'ASC')->findAll();

        $navbar = 'layout/navbar';
        if (session()->has('id_perfil')) {
            if (session('id_perfil') == 1) {
                $navbar = session('modo_cliente') ? 'layout/navbarAdminVisitante' : 'layout/navbarAdmin';
            } elseif (session('id_perfil') == 2) {
                $navbar = 'layout/navbarCliente';
            }
        }

        return view($navbar, ['categoria' => $data['categoria']])
            . view('catalogo', $data)
            . view('layout/footer');
    }


    public function detalle($id)
    {
        $bebidaModel = new Bebida_model();
        $categoria = (new Categoria_model())->orderBy('nombre_categoria', 'ASC')->findAll();
        $bebida= $bebidaModel
            ->select('bebida.*, marca.nombre_marca, categoria.nombre_categoria')
            ->join('marca', 'bebida.id_marca = marca.id_marca')
            ->join('categoria', 'bebida.id_categoria = categoria.id_categoria')
            ->where('bebida.id_bebida', $id)
            ->where('bebida.estado_bebida', 1)
            ->first();

        if (!$bebida) {
            return redirect()->to('/')->with('error', 'Bebida no encontrada');
        }

        $navbar = 'layout/navbar';
        if (session()->has('id_perfil')) {
            if (session('id_perfil') == 1) {
                $navbar = session('modo_cliente') ? 'layout/navbarAdminVisitante' : 'layout/navbarAdmin';
            } elseif (session('id_perfil') == 2) {
                $navbar = 'layout/navbarCliente';
            }
        }

        echo view($navbar, ['categoria' => $categoria]);
        echo view('backend/detalle_bebidas', ['bebida' => $bebida]);
        echo view('layout/footer');
    }


    public function listarBebidas()
    {
        if (session('id_perfil') != 1) {
            return redirect()->to('/');
        }

        $categoriaModel = new Categoria_model();
        $bebidaModel = new Bebida_model();

        // Obtener filtros desde la URL
        $categoriaId = $this->request->getGet('id_categoria');
        $busqueda = $this->request->getGet('busqueda');

        // Construcción de la consulta
        $bebidas = $bebidaModel
            ->select('bebida.*, marca.nombre_marca')
            ->join('marca', 'bebida.id_marca = marca.id_marca');

        if (!empty($categoriaId)) {
            $bebidas->where('bebida.id_categoria', $categoriaId);
        }

        if (!empty($busqueda)) {
            $bebidas->groupStart()
                ->like('bebida.nombre_bebida', $busqueda)
                ->orLike('marca.nombre_marca', $busqueda)
                ->groupEnd();
        }

        $data['bebidas'] = $bebidas->findAll();
        $data['categorias'] = (new Categoria_model())->orderBy('nombre_categoria', 'ASC')->findAll();

        return view('layout/navbarAdmin', $data)
            . view('backend/listar_bebidas', $data)
            . view('layout/footer');
    }


    public function agregarBebida()
    {
        if (session('id_perfil') != 1) return redirect()->to('/');
        $marca = new Marca_model();
        $categoria = new Categoria_model();
        $data['marca'] = $marca->findAll();
        $data['categoria'] = (new Categoria_model())->orderBy('nombre_categoria', 'ASC')->findAll();
        $data['titulo'] = 'Agregar Bebida';
        
        return view ('layout/navbarAdmin', ['categorias' => $categoria]).view('backend/registrar_bebida', $data).view('layout/footer');
    }

   
    public function registrarBebida()
    {
        // 1. Verificación de seguridad
        if (session('id_perfil') != 1) {
            return redirect()->to('/');
        }

        $request = \Config\Services::request();
        $validation = \Config\Services::validation();
        $bebidaModel = new \App\Models\Bebida_model();

        // 2. Definición de Reglas de Validación (Ajustado a nuevas columnas)
        $reglas = [
            'nombre_bebida' => [
                'rules' => 'required|min_length[3]|is_unique[bebida.nombre_bebida]',
                'errors' => [
                    'required' => 'El nombre de la bebida es obligatorio.',
                    'min_length' => 'El nombre debe tener al menos 3 caracteres.',
                    'is_unique' => 'El nombre de la bebida ya está en uso.'
                ]
            ],
            'descripcion_bebida' => [
                'rules' => 'required|min_length[5]',
                'errors' => [
                    'required' => 'La descripción es obligatoria.',
                    'min_length' => 'La descripción debe tener al menos 5 caracteres.'
                ]
            ],
            'precio_bebida' => [
                'rules' => 'required|decimal|greater_than_equal_to[0]',
                'errors' => [
                    'required' => 'El precio es obligatorio.',
                    'decimal' => 'El precio debe ser un número decimal.',
                    'greater_than_equal_to' => 'El precio no puede ser negativo.'
                ]
            ],
            'stock_bebida' => [
                'rules' => 'required|integer|greater_than_equal_to[0]',
                'errors' => [
                    'required' => 'El stock es obligatorio.',
                    'integer' => 'El stock debe ser un número entero.',
                    'greater_than_equal_to' => 'El stock no puede ser negativo.'
                ]
            ],
            'volumen_bebida' => [
                'rules' => 'required|integer|greater_than_equal_to[0]',
                'errors' => [
                    'required' => 'El volumen es obligatorio.',
                    'integer' => 'El volumen debe ser un número entero.',
                    'greater_than_equal_to' => 'El volumen no puede ser negativo.'
                ]
            ],
            'grado_bebida' => [
                'rules' => 'required|decimal|greater_than_equal_to[0]|less_than_equal_to[100]',
                'errors' => [
                    'required' => 'El grado alcohólico es obligatorio.',
                    'decimal' => 'Debe ingresar un número decimal.',
                    'less_than_equal_to' => 'No puede ser mayor a 100%.'
                ]
            ],
            'id_marca' => [
                'rules' => 'required|is_natural_no_zero',
                'errors' => ['required' => 'Debe seleccionar una marca.']
            ],
            'id_categoria' => [
                'rules' => 'required|is_natural_no_zero',
                'errors' => ['required' => 'Debe seleccionar una categoría.']
            ],
            'imagen_bebida' => [
                'rules' => 'uploaded[imagen_bebida]|is_image[imagen_bebida]|max_size[imagen_bebida,2048]',
                'errors' => [
                    'uploaded' => 'Debe subir una imagen.',
                    'is_image' => 'El archivo debe ser una imagen válida.',
                    'max_size' => 'La imagen no debe superar los 2 MB.'
                ]
            ]
        ];
        // 3. Ejecutar Validación

    // 3. Ejecutar Validación
    if (!$this->validate($reglas)) {
        $data['validation'] = $this->validator->getErrors();
        $data['marca'] = (new \App\Models\Marca_model())->findAll();
        $data['categoria'] = (new \App\Models\Categoria_model())->orderBy('nombre_categoria', 'ASC')->findAll();
        
        return $this->renderizarConNavbar('backend/registrar_bebida', $data);
    }

    // 4. Procesar Imagen
    $img = $request->getFile('imagen_bebida');
    $nombre_aleatorio = $img->getRandomName();
    $img->move(ROOTPATH . 'assets/upload', $nombre_aleatorio);

    // 5. Preparar Datos para Inserción (Nombres de la tabla 'bebida')
    $precio = $request->getPost('precio_bebida');
    
    $insertData = [
        'nombre_bebida'      => $request->getPost('nombre_bebida'),
        'descripcion_bebida' => $request->getPost('descripcion_bebida'),
        'precio_bebida'      => $precio,
        'stock_bebida'       => $request->getPost('stock_bebida'),
        'imagen_bebida'      => $nombre_aleatorio,
        'id_marca'           => $request->getPost('id_marca'),
        'volumen_bebida'     => $request->getPost('volumen_bebida'),
        'grado_bebida'       => $request->getPost('grado_bebida'),
        'id_categoria'       => $request->getPost('id_categoria'),
        'estado_bebida'      => 1 // Se registra como activa por defecto
    ];

    $bebidaModel->insert($insertData);

    return redirect()->to('gestionar_bebidas')->with('mensaje', "¡La bebida '{$insertData['nombre_bebida']}' se registró correctamente!");
    }

    public function registrarMarca()
    {
    if (session('id_perfil') != 1) return redirect()->to('/');

    $rules = [
        'nombre_marca' => 'required|min_length[2]|is_unique[marca.nombre_marca]',
    ];

    if (!$this->validate($rules)) {
        return redirect()->back()->with('error', 'La marca ya existe o es inválida.');
    }

    (new \App\Models\Marca_model())->save(['nombre_marca' => $this->request->getPost('nombre_marca')]);
    return redirect()->back()->with('mensaje', 'Marca registrada con éxito.');
    }

    public function registrarCategoria()
    {
    if (session('id_perfil') != 1) return redirect()->to('/');

    $rules = [
        'nombre_categoria' => 'required|min_length[2]|is_unique[categoria.nombre_categoria]',
    ];

    if (!$this->validate($rules)) {
        return redirect()->back()->with('error', 'La categoría ya existe o es inválida.');
    }

    (new \App\Models\Categoria_model())->save(['nombre_categoria' => $this->request->getPost('nombre_categoria')]);
    return redirect()->back()->with('mensaje', 'Categoría registrada con éxito.');
    }

    public function editar($id)
    {
        if (session('id_perfil') != 1) return redirect()->to('/');
        $bebidaModel = new Bebida_model();
        $marcaModel = new Marca_model();
        $categoriaModel = new Categoria_model();

        $bebida = $bebidaModel->find($id);

        if (!$bebida) {
            return redirect()->to(base_url('listar_bebidas'))->with('mensaje', 'Bebida no encontrada.');
        }

        return view('layout/navbarAdmin', ['categoria' => $categoriaModel ->findAll()]).view('backend/registrar_bebida', [
            'bebida' => $bebida,
            'marca' => $marcaModel->findAll(),
            'categoria' => $categoriaModel->orderBy('nombre_categoria', 'ASC')->findAll(),
            'validation' => []
        ]).view('layout/footer');
    }


    public function eliminar($id)
    {
        if (session('id_perfil') != 1) return redirect()->to('/');

        (new Bebida_model())->delete($id);

        return redirect()->to('listar_bebidas')->with('mensaje', 'Bebida eliminada correctamente.');
    }

    public function deshabilitar($id)
    {
        if (session('id_perfil') != 1) return redirect()->to('/');
        $bebidaModel = new Bebida_model();
        $bebidaModel->update($id, ['estado_bebida' => 0]);
        session()->setFlashdata('mensaje', 'La bebida fue deshabilitada correctamente.');
        session()->setFlashdata('tipo_mensaje', 'warning'); // puede ser success, danger, info, warning
        return redirect()->to('gestionar_bebidas');
    }

    public function habilitar($id)
    {
        if (session('id_perfil') != 1) return redirect()->to('/');
        $bebidaModel = new Bebida_model();
        $bebidaModel->update($id, ['estado_bebida' => 1]);
        session()->setFlashdata('mensaje', 'La bebida fue habilitada correctamente.');
        session()->setFlashdata('tipo_mensaje', 'success'); // puede ser success, danger, info, warning
        return redirect()->to('gestionar_bebidas');
    }

    public function gestionarBebidas()
    {
        if (session('id_perfil') != 1) return redirect()->to('/');
        
        $bebidaModel = new \App\Models\Bebida_model();
        $categoriaModel = new \App\Models\Categoria_model();

        $busqueda = $this->request->getGet('busqueda');
        $categoriaSeleccionada = $this->request->getGet('categoria');

        $bebidaModel
            ->select('bebida.*, marca.nombre_marca, categoria.nombre_categoria')
            ->join('marca', 'bebida.id_marca = marca.id_marca', 'left')
            ->join('categoria', 'bebida.id_categoria = categoria.id_categoria', 'left');

        if ($busqueda) {
            $bebidaModel->groupStart()
                ->like('bebida.nombre_bebida', $busqueda)
                ->orLike('marca.nombre_marca', $busqueda)
                ->groupEnd();
        }

        if ($categoriaSeleccionada) {
            $bebidaModel->where('bebida.id_categoria', $categoriaSeleccionada);
        }

        $bebidas = $bebidaModel->findAll();
        $categorias = $categoriaModel->orderBy('nombre_categoria', 'ASC')->findAll();

        return view('layout/navbarAdmin', ['categoria' => $categorias])
            . view('backend/gestionar_bebidas', [
                'bebida' => $bebidas,
                'categoria' => $categorias,
                'busqueda' => $busqueda,
                'categoriaSeleccionada' => $categoriaSeleccionada
            ])
            . view('layout/footer');
    }

    public function actualizarBebida($id)
{
    helper(['form', 'url']);
    if (session('id_perfil') != 1) return redirect()->to('/');

    $bebidaModel = new \App\Models\Bebida_model();
    $bebida = $bebidaModel->find($id);

    if (!$bebida) {
        return redirect()->to('gestionar_bebidas')->with('mensaje', 'Bebida no encontrada.');
    }

    $request = \Config\Services::request();

    // 1. Reglas de validación: eliminamos 'uploaded' porque la imagen es opcional al editar
    $reglas = [
        'nombre_bebida' => "required|min_length[3]|is_unique[bebida.nombre_bebida,id_bebida,$id]",
        'descripcion_bebida' => 'required|min_length[5]',
        'precio_bebida' => 'required|decimal|greater_than_equal_to[0]',
        'stock_bebida' => 'required|integer|greater_than_equal_to[0]',
        'volumen_bebida' => 'required|integer|greater_than_equal_to[0]',
        'grado_bebida' => 'required|decimal|greater_than_equal_to[0]|less_than_equal_to[100]',
        'id_marca' => 'required|is_natural_no_zero',
        'id_categoria' => 'required|is_natural_no_zero',
    ];
    // Solo validamos la imagen SI el usuario eligió un archivo nuevo
    if ($request->getFile('imagen_bebida')->isValid()) {
        $reglas['imagen_bebida'] = 'is_image[imagen_bebida]|max_size[imagen_bebida,2048]';
    }

    // 2. Validar (usamos withInput para no perder los datos escritos al fallar)
    if (! $this->validate($reglas)) {
        $data = [
            'bebida'     => $bebida,
            'marca'     => (new \App\Models\Marca_model())->findAll(),
            'categoria' => (new \App\Models\Categoria_model())->orderBy('nombre_categoria', 'ASC')->findAll(),
            'validation' => $this->validator
        ];
        
        return view('layout/navbarAdmin')
             . view('backend/registrar_bebida', $data)
             . view('layout/footer');
    }
    // 3. Preparar datos
    $datos = [
        'nombre_bebida'      => $request->getPost('nombre_bebida'),
        'descripcion_bebida' => $request->getPost('descripcion_bebida'),
        'precio_bebida'      => (float) $request->getPost('precio_bebida'),
        'stock_bebida'       => $request->getPost('stock_bebida'),
        'volumen_bebida'     => $request->getPost('volumen_bebida'),
        'grado_bebida'       => $request->getPost('grado_bebida'),
        'id_marca'           => $request->getPost('id_marca'),
        'id_categoria'       => $request->getPost('id_categoria'),
    ];

    // 4. Procesar imagen nueva solo si existe
    $imagen = $request->getFile('imagen_bebida');
    if ($imagen && $imagen->isValid() && !$imagen->hasMoved()) {
        $nombreImagen = $imagen->getRandomName();
        $imagen->move('assets/upload', $nombreImagen);

        // Eliminar imagen anterior si existe
        if (!empty($bebida['imagen_bebida']) && file_exists('assets/upload/' . $bebida['imagen_bebida'])) {
            unlink('assets/upload/' . $bebida['imagen_bebida']);
        }
        $datos['imagen_bebida'] = $nombreImagen;
    }

    // 5. Actualizar
    $bebidaModel->update($id, $datos);

    return redirect()->to('gestionar_bebidas')->with('mensaje', 'Producto actualizado correctamente.');
}
}
