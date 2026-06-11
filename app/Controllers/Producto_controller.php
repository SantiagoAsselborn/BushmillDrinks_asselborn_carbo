<?php

namespace App\Controllers;

use App\Models\Bebida_model;
use App\Models\Marca_model;
use App\Models\Categoria_model;

class Producto_controller extends BaseController
{
    // VALIDAR DATOS BEBIDA
    // Operación: validarDatosBebida(id_bebida)
    private function validarDatosBebida($id_bebida = null)
    {
        $rules = [
            'nombre_bebida' => [
                'rules' => $id_bebida
                    ? "required|min_length[3]|is_unique[bebida.nombre_bebida,id_bebida,$id_bebida]"
                    : 'required|min_length[3]|is_unique[bebida.nombre_bebida]',
                'errors' => [
                    'required' => 'El nombre de la bebida es obligatorio.',
                    'min_length' => 'El nombre debe tener al menos 3 caracteres.',
                    'is_unique' => 'Ya existe una bebida con ese nombre.'
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
                    'decimal' => 'Debe ingresar un precio válido.'
                ]
            ],

            'stock_bebida' => [
                'rules' => 'required|integer|greater_than_equal_to[0]',
                'errors' => [
                    'required' => 'El stock es obligatorio.',
                    'integer' => 'El stock debe ser numérico.'
                ]
            ],

            'volumen_bebida' => [
                'rules' => 'required|integer|greater_than_equal_to[0]',
                'errors' => [
                    'required' => 'El volumen es obligatorio.'
                ]
            ],

            'grado_bebida' => [
                'rules' => 'required|decimal|greater_than_equal_to[0]|less_than_equal_to[100]',
                'errors' => [
                    'required' => 'El grado alcohólico es obligatorio.',
                    'decimal' => 'Debe ser un número válido.'
                ]
            ],

            'id_marca' => [
                'rules' => 'required|is_natural_no_zero',
                'errors' => [
                    'required' => 'Debe seleccionar una marca.'
                ]
            ],

            'id_categoria' => [
                'rules' => 'required|is_natural_no_zero',
                'errors' => [
                    'required' => 'Debe seleccionar una categoría.'
                ]
            ]
        ];

        // Imagen obligatoria SOLO al registrar
        if ($id_bebida == null) {

            $rules['imagen_bebida'] = [
                'rules' => 'uploaded[imagen_bebida]|is_image[imagen_bebida]|max_size[imagen_bebida,2048]',
                'errors' => [
                    'uploaded' => 'Debe subir una imagen.',
                    'is_image' => 'El archivo debe ser una imagen válida.',
                    'max_size' => 'La imagen no debe superar los 2MB.'
                ]
            ];

        } else {

            // En edición la imagen es opcional
            if ($this->request->getFile('imagen_bebida')->isValid()) {

                $rules['imagen_bebida'] = [
                    'rules' => 'is_image[imagen_bebida]|max_size[imagen_bebida,2048]',
                    'errors' => [
                        'is_image' => 'El archivo debe ser una imagen válida.',
                        'max_size' => 'La imagen no debe superar los 2MB.'
                    ]
                ];
            }
        }

        return $this->validate($rules);
    }

    // =========================================================
    // INDEX
    // =========================================================
    public function index()
    {
        $bebidaModel = new Bebida_model();

        $bebidas = $bebidaModel
            ->select('bebida.*, marca.nombre_marca')
            ->join('marca', 'bebida.id_marca = marca.id_marca')
            ->where('bebida.estado_bebida', 1)
            ->findAll();

        return $this->renderizarConNavbar('nueva_plantilla', [
            'bebida' => $bebidas
        ]);
    }

    // =========================================================
    // CATÁLOGO
    // =========================================================
    public function catalogo()
    {
        $bebidaModel = new Bebida_model();

        $marca = $this->request->getGet('marca');
        $categoria = $this->request->getGet('categoria');
        $precio_max = $this->request->getGet('precio_max');

        $builder = $bebidaModel
            ->select('bebida.*, marca.nombre_marca, categoria.nombre_categoria')
            ->join('marca', 'bebida.id_marca = marca.id_marca')
            ->join('categoria', 'bebida.id_categoria = categoria.id_categoria')
            ->where('bebida.estado_bebida', 1)
            ->orderBy('categoria.nombre_categoria', 'ASC')
            ->orderBy('bebida.nombre_bebida', 'ASC');

        if (!empty($marca)) {
            $builder->like('marca.nombre_marca', $marca);
        }

        if (!empty($categoria)) {
            $builder->like('categoria.nombre_categoria', $categoria);
        }

        if (!empty($precio_max)) {
            $builder->where('bebida.precio_bebida <=', $precio_max);
        }

        $data = [
            'bebida' => $builder->findAll(),
            'marca' => (new Marca_model())->findAll(),
            'categoria' => (new Categoria_model())
                ->orderBy('nombre_categoria', 'ASC')
                ->findAll()
        ];

        return $this->renderizarConNavbar('catalogo', $data);
    }

    // =========================================================
    // DETALLE
    // Operación: detalle(id_bebida)
    // =========================================================
    public function detalle($id_bebida)
    {
        $bebidaModel = new Bebida_model();

        $bebida = $bebidaModel
            ->select('bebida.*, marca.nombre_marca, categoria.nombre_categoria')
            ->join('marca', 'bebida.id_marca = marca.id_marca')
            ->join('categoria', 'bebida.id_categoria = categoria.id_categoria')
            ->where('bebida.id_bebida', $id_bebida)
            ->where('bebida.estado_bebida', 1)
            ->first();

        if (!$bebida) {
            return redirect()->to('/')
                ->with('error', 'Bebida no encontrada');
        }

        return $this->renderizarConNavbar(
            'backend/detalle_bebidas',
            ['bebida' => $bebida]
        );
    }

    // =========================================================
    // LISTAR BEBIDAS
    // =========================================================
    public function listarBebidas()
    {
        if (session('id_perfil') != 1) {
            return redirect()->to('/');
        }

        $bebidaModel = new Bebida_model();

        $categoriaId = $this->request->getGet('id_categoria');
        $busqueda = $this->request->getGet('busqueda');

        $bebidas = $bebidaModel
            ->select('bebida.*, marca.nombre_marca, categoria.nombre_categoria')
            ->join('marca', 'bebida.id_marca = marca.id_marca')
            ->join('categoria', 'bebida.id_categoria = categoria.id_categoria');

        if (!empty($categoriaId)) {
            $bebidas->where('bebida.id_categoria', $categoriaId);
        }

        if (!empty($busqueda)) {

            $bebidas->groupStart()
                ->like('bebida.nombre_bebida', $busqueda)
                ->orLike('marca.nombre_marca', $busqueda)
                ->groupEnd();
        }

        $data = [
            'bebidas' => $bebidas->findAll(),
            'categorias' => (new Categoria_model())
                ->orderBy('nombre_categoria', 'ASC')
                ->findAll()
        ];

        return $this->renderizarConNavbar(
            'backend/listar_bebidas',
            $data
        );
    }

    // =========================================================
    // FORM AGREGAR BEBIDA
    // =========================================================
    public function agregarBebida()
    {
        if (session('id_perfil') != 1) {
            return redirect()->to('/');
        }

        $data = [
            'marca' => (new Marca_model())->findAll(),
            'categoria' => (new Categoria_model())
                ->orderBy('nombre_categoria', 'ASC')
                ->findAll(),
            'titulo' => 'Agregar Bebida'
        ];

        return $this->renderizarConNavbar(
            'backend/registrar_bebida',
            $data
        );
    }

    // =========================================================
    // REGISTRAR BEBIDA
    // Operación: registrarBebida(id_bebida)
    // =========================================================
    public function registrarBebida()
    {
        if (session('id_perfil') != 1) {
            return redirect()->to('/');
        }

        $bebidaModel = new Bebida_model();

        // VALIDAR
        if (!$this->validarDatosBebida()) {

            return $this->renderizarConNavbar(
                'backend/registrar_bebida',
                [
                    'validation' => $this->validator,
                    'marca' => (new Marca_model())->findAll(),
                    'categoria' => (new Categoria_model())
                        ->orderBy('nombre_categoria', 'ASC')
                        ->findAll()
                ]
            );
        }

        //Insertamos la imagen
        $imagen = $this->request->getFile('imagen_bebida');
        //Validamos la imagen antes de moverla para evitar errores
        if (!$imagen->isValid()) {
            return redirect()->back()
                ->with('error', 'La imagen no pudo cargarse.');
        }
        $nombreImagen = $imagen->getRandomName();
        $imagen->move(ROOTPATH . 'assets/upload', $nombreImagen);

        // PROCEDIMIENTO ALMACENADO INSERTAR BEBIDA
        $db = \Config\Database::connect();

        $db->query("CALL sp_insertar_bebida(?, ?, ?, ?, ?, ?, ?, ?, ?)", [

            $this->request->getPost('nombre_bebida'),

            $this->request->getPost('descripcion_bebida'),

            $this->request->getPost('precio_bebida'),

            $this->request->getPost('stock_bebida'),

            $nombreImagen,

            $this->request->getPost('volumen_bebida'),

            $this->request->getPost('grado_bebida'),

            $this->request->getPost('id_categoria'),

            $this->request->getPost('id_marca')
        ]);

        return redirect()->to('gestionar_bebidas')
            ->with('mensaje', 'Bebida registrada correctamente.');
    }

    public function registrarMarca()
{
    if (session('id_perfil') != 1) {

        return redirect()->to('/');
    }

    $marcaModel = new Marca_model();

    $nombre_marca =
        trim(
            $this->request->getPost('nombre_marca')
        );

    // VALIDAR VACÍO

    if (empty($nombre_marca)) {

        return redirect()->back()
            ->with(
                'error',
                'Debe ingresar un nombre de marca.'
            );
    }

    // VALIDAR DUPLICADO

    $marcaExistente = $marcaModel
        ->where(
            'nombre_marca',
            $nombre_marca
        )
        ->first();

    if ($marcaExistente) {

        return redirect()->back()
            ->with(
                'error',
                'La marca ya existe.'
            );
    }

    // INSERTAR

    $marcaModel->insert([

        'nombre_marca' =>
            $nombre_marca
    ]);

    return redirect()->back()
        ->with(
            'mensaje',
            'Marca registrada correctamente.'
        );
}

    public function registrarCategoria()
{
    if (session('id_perfil') != 1) {
        return redirect()->to('/');
    }

    $categoriaModel = new Categoria_model();

    $nombreCategoria = trim(
        $this->request->getPost('nombre_categoria')
    );

    if (empty($nombreCategoria)) {

        return redirect()->back()
            ->with('error', 'Debe ingresar un nombre de categoría.');
    }

    // Verificar duplicados
    $existe = $categoriaModel
        ->where('nombre_categoria', $nombreCategoria)
        ->first();

    if ($existe) {

        return redirect()->back()
            ->with(
                'error',
                'Ya existe una categoría con ese nombre.'
            );
    }

    $categoriaModel->insert([
        'nombre_categoria' => $nombreCategoria
    ]);

    return redirect()->back()
        ->with(
            'mensaje',
            'Categoría registrada correctamente.'
        );
}

    // =========================================================
    // EDITAR
    // Operación: editar(id_bebida)
    // =========================================================
    public function editar($id_bebida)
    {
        if (session('id_perfil') != 1) {
            return redirect()->to('/');
        }

        $bebidaModel = new Bebida_model();

        $bebida = $bebidaModel->find($id_bebida);

        if (!$bebida) {
            return redirect()->to('gestionar_bebidas')
                ->with('mensaje', 'Bebida no encontrada.');
        }

        return $this->renderizarConNavbar(
            'backend/registrar_bebida',
            [
                'bebida' => $bebida,
                'marca' => (new Marca_model())->findAll(),
                'categoria' => (new Categoria_model())
                    ->orderBy('nombre_categoria', 'ASC')
                    ->findAll()
            ]
        );
    }

    // =========================================================
    // ACTUALIZAR BEBIDA
    // Operación: actualizarBebida(id_bebida)
    // =========================================================
    public function actualizarBebida($id_bebida)
{
    if (session('id_perfil') != 1) {
        return redirect()->to('/');
    }

    $bebidaModel = new Bebida_model();

    $bebida = $bebidaModel->find($id_bebida);

    if (!$bebida) {

        return redirect()->to('gestionar_bebidas')
            ->with('mensaje', 'Bebida no encontrada.');
    }

    // =========================================
    // VALIDAR
    // =========================================

    if (!$this->validarDatosBebida($id_bebida)) {

        return $this->renderizarConNavbar(
            'backend/registrar_bebida',
            [
                'bebida' => $bebida,

                'marca' =>
                    (new Marca_model())->findAll(),

                'categoria' =>
                    (new Categoria_model())
                        ->orderBy(
                            'nombre_categoria',
                            'ASC'
                        )
                        ->findAll(),

                'validation' => $this->validator
            ]
        );
    }

    // =========================================
    // DATOS
    // =========================================

    $datos = [

        'nombre_bebida' =>
            $this->request
                ->getPost('nombre_bebida'),

        'descripcion_bebida' =>
            $this->request
                ->getPost('descripcion_bebida'),

        'precio_bebida' =>
            $this->request
                ->getPost('precio_bebida'),

        'stock_bebida' =>
            $this->request
                ->getPost('stock_bebida'),

        'volumen_bebida' =>
            $this->request
                ->getPost('volumen_bebida'),

        'grado_bebida' =>
            $this->request
                ->getPost('grado_bebida'),

        'id_marca' =>
            $this->request
                ->getPost('id_marca'),

        'id_categoria' =>
            $this->request
                ->getPost('id_categoria'),
    ];

    // =========================================
    // NUEVA IMAGEN
    // =========================================

    $imagen = $this->request
        ->getFile('imagen_bebida');

    if (
        $imagen &&
        $imagen->isValid() &&
        !$imagen->hasMoved()
    ) {

        $nombreImagen =
            $imagen->getRandomName();

        $imagen->move(
            ROOTPATH . 'assets/upload',
            $nombreImagen
        );

        // BORRAR IMAGEN ANTERIOR

        if (
            !empty($bebida['imagen_bebida']) &&
            file_exists(
                ROOTPATH .
                'assets/upload/' .
                $bebida['imagen_bebida']
            )
        ) {

            unlink(
                ROOTPATH .
                'assets/upload/' .
                $bebida['imagen_bebida']
            );
        }

        $datos['imagen_bebida'] =
            $nombreImagen;
    }


    // PROCEDIMIENTO ALMACENADO ACTUALIZAR BEBIDA

    $db = \Config\Database::connect();

    try {

        $db->query(
            "CALL sp_actualizar_bebida(
                ?, ?, ?, ?, ?, ?, ?, ?, ?
            )",
            [

                $id_bebida,

                $datos['nombre_bebida'],

                $datos['descripcion_bebida'],

                $datos['precio_bebida'],

                $datos['stock_bebida'],

                $datos['volumen_bebida'],

                $datos['grado_bebida'],

                $datos['id_categoria'],

                $datos['id_marca']
            ]
        );

        // =====================================
        // ACTUALIZAR IMAGEN
        // =====================================

        if (
            isset($datos['imagen_bebida'])
        ) {

            $bebidaModel->update(
                $id_bebida,
                [
                    'imagen_bebida' =>
                        $datos['imagen_bebida']
                ]
            );
        }

    } catch (\Exception $e) {

        return redirect()->back()
            ->with(
                'error',
                $e->getMessage()
            );
    }

    return redirect()->to(
        'gestionar_bebidas'
    )->with(
        'mensaje',
        'Bebida actualizada correctamente.'
    );
}

    // =========================================================
    // ELIMINAR
    // Operación: eliminar(id_bebida)
    // =========================================================
    public function eliminar($id_bebida)
    {
        if (session('id_perfil') != 1) {
            return redirect()->to('/');
        }

        (new Bebida_model())->delete($id_bebida);

        return redirect()->to('gestionar_bebidas')
            ->with('mensaje', 'Bebida eliminada correctamente.');
    }

    // =========================================================
    // DESHABILITAR
    // =========================================================
    public function deshabilitar($id_bebida)
    {
        if (session('id_perfil') != 1) {
            return redirect()->to('/');
        }

        (new Bebida_model())->update($id_bebida, [
            'estado_bebida' => 0
        ]);

        return redirect()->to('gestionar_bebidas')
            ->with('mensaje', 'Bebida deshabilitada correctamente.');
    }

    // =========================================================
    // HABILITAR
    // =========================================================
    public function habilitar($id_bebida)
    {
        if (session('id_perfil') != 1) {
            return redirect()->to('/');
        }

        (new Bebida_model())->update($id_bebida, [
            'estado_bebida' => 1
        ]);

        return redirect()->to('gestionar_bebidas')
            ->with('mensaje', 'Bebida habilitada correctamente.');
    }

    // =========================================================
    // GESTIONAR BEBIDAS
    // =========================================================
    public function gestionarBebidas()
    {
        if (session('id_perfil') != 1) {
            return redirect()->to('/');
        }

        $bebidaModel = new Bebida_model();

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
            $bebidaModel->where(
                'bebida.id_categoria',
                $categoriaSeleccionada
            );
        }

        $data = [

            'bebida' => $bebidaModel->findAll(),

            'categoria' => (new Categoria_model())
                ->orderBy('nombre_categoria', 'ASC')
                ->findAll(),

            'busqueda' => $busqueda,

            'categoriaSeleccionada' => $categoriaSeleccionada
        ];

        return $this->renderizarConNavbar(
            'backend/gestionar_bebidas',
            $data
        );
    }
}
