<?php

namespace App\Controllers;

use App\Models\Bebida_model;
use App\Models\Marca_model;
use App\Models\Categoria_model;
use App\Models\Promocion_model; // Añadido el namespace del modelo de promociones

class Producto_controller extends BaseController
{
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
            // En caso que estemos editando/actualizando la bebida, la imagen es opcional
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

    public function catalogo()
    {
        $bebidaModel = new Bebida_model();
        $marca = $this->request->getGet('marca');
        $categoria = $this->request->getGet('categoria');
        $precio_max = $this->request->getGet('precio_max');

        // Agregamos los campos de promoción al select y su correspondiente LEFT JOIN
        $builder = $bebidaModel
            ->select('bebida.*, marca.nombre_marca, categoria.nombre_categoria, promocion.tipo_promocion, promocion.valor_promocion, promocion.fecha_inicio, promocion.fecha_fin, promocion.estado_promocion')
            ->join('marca', 'bebida.id_marca = marca.id_marca')
            ->join('categoria', 'bebida.id_categoria = categoria.id_categoria')
            ->join('promocion', 'bebida.id_bebida = promocion.id_bebida AND promocion.estado_promocion = 1', 'left') // <-- Crucial
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

        $marcaModel = new Marca_model();
        $categoriaModel = new Categoria_model();

        $data = [
            'bebida'    => $builder->findAll(),
            'marca'     => $marcaModel->listar_marcas(),
            'categoria' => $categoriaModel->listar_categorias()
        ];

        return $this->renderizarConNavbar('catalogo', $data);
    }

    // Operación: detalle(id_bebida)
    // Operación: detalle(id_bebida)
    public function detalle($id_bebida)
    {
        $bebidaModel = new Bebida_model();

        // Agregamos las columnas de promociones para la vista de detalle particular
        $bebida = $bebidaModel
            ->select('bebida.*, marca.nombre_marca, categoria.nombre_categoria, promocion.tipo_promocion, promocion.valor_promocion, promocion.fecha_inicio, promocion.fecha_fin, promocion.estado_promocion')
            ->join('marca', 'bebida.id_marca = marca.id_marca')
            ->join('categoria', 'bebida.id_categoria = categoria.id_categoria')
            ->join('promocion', 'bebida.id_bebida = promocion.id_bebida AND promocion.estado_promocion = 1', 'left') // <-- Crucial
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
        $categoriaModel = new Categoria_model();
        $data = [
            'bebidas'    => $bebidas->findAll(),
            'categorias' => $categoriaModel->listar_categorias()
        ];
        return $this->renderizarConNavbar(
            'backend/listar_bebidas',
            $data
        );
    }

    // Formulario para agregar bebida
    public function agregarBebida()
    {
        if (session('id_perfil') != 1) {
            return redirect()->to('/');
        }
        $marcaModel = new Marca_model();
        $categoriaModel = new Categoria_model();
        $data = [
            'marca'     => $marcaModel->listar_marcas(),
            'categoria' => $categoriaModel->listar_categorias(),
            'titulo'    => 'Agregar Bebida'
        ];
        return $this->renderizarConNavbar(
            'backend/registrar_bebida',
            $data
        );
    }

    // Operación: registrarBebida(id_bebida)
    public function registrarBebida()
    {
        if (session('id_perfil') != 1) {
            return redirect()->to('/');
        }
        $bebidaModel = new Bebida_model();
        $promocionModel = new Promocion_model();
        $marcaModel = new Marca_model();
        $categoriaModel = new Categoria_model();

        // CORRECCIÓN: Se pasa null explícito por ser registro nuevo
        if (!$this->validarDatosBebida(null)) {
            return $this->renderizarConNavbar(
                'backend/registrar_bebida',
                [
                    'validation' => $this->validator,
                    'marca'      => $marcaModel->listar_marcas(),
                    'categoria'  => $categoriaModel->listar_categorias() // CORRECCIÓN: typo de listar_categories()
                ]
            );
        }

        $imagen = $this->request->getFile('imagen_bebida');
        if (!$imagen->isValid()) {
            return redirect()->back()
                ->with('error', 'La imagen no pudo cargarse.');
        }
        $nombreImagen = $imagen->getRandomName();
        $imagen->move(ROOTPATH . 'assets/upload', $nombreImagen);

        $db = \Config\Database::connect();
        $db->transStart();

        // PROCEDIMIENTO ALMACENADO INSERTAR BEBIDA
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

        $idBebidaCreada = $db->insertID();

        // GUARDAR LA PROMOCIÓN
        if ($this->request->getPost('aplicar_promocion') == '1') {
            $datosPromo = [
                'id_bebida'        => $idBebidaCreada,
                'tipo_promocion'   => $this->request->getPost('tipo_promocion'),
                'valor_promocion'  => $this->request->getPost('valor_promocion'),
                'fecha_inicio'     => $this->request->getPost('fecha_inicio'),
                'fecha_fin'        => $this->request->getPost('fecha_fin'),
                'estado_promocion' => 1
            ];
            $promocionModel->crear_promocion($datosPromo);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Ocurrió un error al registrar la bebida o su promoción.');
        }

        return redirect()->to('gestionar_bebidas')
            ->with('mensaje', 'Bebida registrada correctamente.');
    }

    public function registrarMarca()
    {
        if (session('id_perfil') != 1) {
            return redirect()->to('/');
        }
        $marcaModel = new Marca_model();
        $nombre_marca = trim($this->request->getPost('nombre_marca'));
        if (empty($nombre_marca)) {
            return redirect()->back()->with('error', 'Debe ingresar un nombre de marca.');
        }
        $marcaExistente = $marcaModel->where('nombre_marca', $nombre_marca)->first();
        if ($marcaExistente) {
            return redirect()->back()->with('error', 'La marca ya existe.');
        }
        $marcaModel->registrar_marca($nombre_marca);
        return redirect()->back()->with('mensaje', 'Marca registrada correctamente.');
    }

    public function registrarCategoria()
    {
        if (session('id_perfil') != 1) {
            return redirect()->to('/');
        }
        $categoriaModel = new Categoria_model();
        $nombreCategoria = trim($this->request->getPost('nombre_categoria'));
        if (empty($nombreCategoria)) {
            return redirect()->back()->with('error', 'Debe ingresar un nombre de categoría.');
        }
        $existe = $categoriaModel->where('nombre_categoria', $nombreCategoria)->first();
        if ($existe) {
            return redirect()->back()->with('error', 'Ya existe una categoría con ese nombre.');
        }
        $categoriaModel->registrar_categoria($nombreCategoria);
        return redirect()->back()->with('mensaje', 'Categoría registrada correctamente.');
    }

    // Operación: editar(id_bebida)
    public function editar($id_bebida)
    {
        if (session('id_perfil') != 1) {
            return redirect()->to('/');
        }
        $bebidaModel = new Bebida_model();
        $bebida = $bebidaModel->find($id_bebida);
        if (!$bebida) {
            return redirect()->to('gestionar_bebidas')->with('mensaje', 'Bebida no encontrada.');
        }
        $promocionModel = new Promocion_model();
        $promocion = $promocionModel->where('id_bebida', $id_bebida)->first();
        $marcaModel = new Marca_model();
        $categoriaModel = new Categoria_model();
        return $this->renderizarConNavbar(
            'backend/registrar_bebida',
            [
                'bebida'    => $bebida,
                'promocion' => $promocion,
                'marca'     => $marcaModel->listar_marcas(),
                'categoria' => $categoriaModel->listar_categorias()
            ]
        );
    }

    // Operación: actualizarBebida(id_bebida)
    public function actualizarBebida($id_bebida)
    {
        if (session('id_perfil') != 1) {
            return redirect()->to('/');
        }

        $bebidaModel = new Bebida_model();
        $promocionModel = new Promocion_model();

        $bebida = $bebidaModel->find($id_bebida);
        if (!$bebida) {
            return redirect()->to('gestionar_bebidas')->with('mensaje', 'Bebida no encontrada.');
        }

        // VALIDACIÓN
        if (!$this->validarDatosBebida($id_bebida)) {
            $marcaModel = new Marca_model();
            $categoriaModel = new Categoria_model();
            $promocion = $promocionModel->where('id_bebida', $id_bebida)->first();
            return $this->renderizarConNavbar(
                'backend/registrar_bebida',
                [
                    'bebida'     => $bebida,
                    'promocion'  => $promocion,
                    'marca'      => $marcaModel->listar_marcas(),
                    'categoria'  => $categoriaModel->listar_categorias(),
                    'validation' => $this->validator
                ]
            );
        }

        $datos = [
            'nombre_bebida'      => $this->request->getPost('nombre_bebida'),
            'descripcion_bebida' => $this->request->getPost('descripcion_bebida'),
            'precio_bebida'      => $this->request->getPost('precio_bebida'),
            'stock_bebida'       => $this->request->getPost('stock_bebida'),
            'volumen_bebida'     => $this->request->getPost('volumen_bebida'),
            'grado_bebida'       => $this->request->getPost('grado_bebida'),
            'id_marca'           => $this->request->getPost('id_marca'),
            'id_categoria'       => $this->request->getPost('id_categoria'),
        ];

        // GESTIÓN DE IMAGEN
        $imagen = $this->request->getFile('imagen_bebida');
        if ($imagen && $imagen->isValid() && !$imagen->hasMoved()) {
            $nombreImagen = $imagen->getRandomName();
            $imagen->move(ROOTPATH . 'assets/upload', $nombreImagen);
            if (!empty($bebida['imagen_bebida']) && file_exists(ROOTPATH . 'assets/upload/' . $bebida['imagen_bebida'])) {
                unlink(ROOTPATH . 'assets/upload/' . $bebida['imagen_bebida']);
            }
            $datos['imagen_bebida'] = $nombreImagen;
        }

        $db = \Config\Database::connect();

        try {
            // 1. INICIAR TRANSACCIÓN DE FORMA NATIVA EN SQL
            $db->query("START TRANSACTION");

            // 2. EJECUTAR EL PROCEDIMIENTO ALMACENADO
            $db->query("CALL sp_actualizar_bebida(?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                $id_bebida,
                $datos['nombre_bebida'],
                $datos['descripcion_bebida'],
                $datos['precio_bebida'],
                $datos['stock_bebida'],
                $datos['volumen_bebida'],
                $datos['grado_bebida'],
                $datos['id_categoria'],
                $datos['id_marca']
            ]);

            // Sincronizar y limpiar los buffers que deja el procedimiento almacenado en MySQL
            if ($db->connID instanceof \mysqli) {
                while ($db->connID->more_results()) {
                    $db->connID->next_result();
                }
            }

            // Actualizar la imagen si se subió una nueva
            if (isset($datos['imagen_bebida'])) {
                $db->table('bebida')->where('id_bebida', $id_bebida)->update(['imagen_bebida' => $datos['imagen_bebida']]);
            }

            // 3. GESTIÓN DE PROMOCIÓN
            $promoExistente = $db->table('promocion')->where('id_bebida', $id_bebida)->get()->getRowArray();

            if ($this->request->getPost('aplicar_promocion') == '1') {

                $fecha_in_raw = $this->request->getPost('fecha_inicio');
                $fecha_fi_raw = $this->request->getPost('fecha_fin');

                $fecha_inicio = !empty($fecha_in_raw) ? date('Y-m-d H:i:s', strtotime($fecha_in_raw)) : date('Y-m-d H:i:s');
                $fecha_fin    = !empty($fecha_fi_raw) ? date('Y-m-d H:i:s', strtotime($fecha_fi_raw)) : date('Y-m-d H:i:s', strtotime('+1 month'));

                $valor_promocion = $this->request->getPost('valor_promocion');
                if ($valor_promocion === '' || $valor_promocion === null) {
                    $valor_promocion = 0.00;
                }

                $datosPromo = [
                    'id_bebida'        => $id_bebida,
                    'tipo_promocion'   => $this->request->getPost('tipo_promocion') ?: 'descuento',
                    'valor_promocion'  => $valor_promocion,
                    'fecha_inicio'     => $fecha_inicio,
                    'fecha_fin'        => $fecha_fin,
                    'estado_promocion' => 1
                ];

                if ($promoExistente) {
                    // Si existía, se actualizan los valores
                    $db->table('promocion')
                        ->where('id_promocion', $promoExistente['id_promocion'])
                        ->update($datosPromo);
                } else {
                    // Si es nueva, se inserta limpiamente
                    $db->table('promocion')->insert($datosPromo);
                }
            } else {
                // Si el switch está apagado pero existía una promo, baja lógica
                if ($promoExistente) {
                    $db->table('promocion')
                        ->where('id_promocion', $promoExistente['id_promocion'])
                        ->update(['estado_promocion' => 0]);
                }
            }

            // 4. SI TODO SALIÓ BIEN, MANDAMOS EL COMMIT NATIVO
            $db->query("COMMIT");
        } catch (\Exception $e) {
            // SI ALGO FALLÓ (en el SP, la imagen o la promo), DESACEMOS TODO CON UN ROLLBACK NATIVO
            $db->query("ROLLBACK");
            return redirect()->back()->withInput()->with('error', 'Error al guardar los datos: ' . $e->getMessage());
        }

        return redirect()->to('gestionar_bebidas')->with('mensaje', 'Bebida actualizada correctamente.');
    }
    // Operación: deshabilitar(id_bebida)
    public function deshabilitar($id_bebida)
    {
        if (session('id_perfil') != 1) {
            return redirect()->to('/');
        }
        $bebidaModel = new Bebida_model();
        $bebida = $bebidaModel->find($id_bebida);
        if (!$bebida) {
            return redirect()->to('gestionar_bebidas')->with('error', 'Bebida no encontrada.');
        }

        // CORRECCIÓN: Uso de la instancia $bebidaModel local
        $bebidaModel->update($id_bebida, ['estado_bebida' => 0]);
        return redirect()->to('gestionar_bebidas')->with('mensaje', 'Bebida deshabilitada correctamente.');
    }

    // Operación: habilitar(id_bebida)
    public function habilitar($id_bebida)
    {
        if (session('id_perfil') != 1) {
            return redirect()->to('/');
        }
        $bebidaModel = new Bebida_model();

        // CORRECCIÓN: Uso de la instancia $bebidaModel local
        $bebidaModel->update($id_bebida, ['estado_bebida' => 1]);
        return redirect()->to('gestionar_bebidas')->with('mensaje', 'Bebida habilitada correctamente.');
    }

    // Operación: gestionarBebidas()
    public function gestionarBebidas()
    {
        if (session('id_perfil') != 1) {
            return redirect()->to('/');
        }

        $bebidaModel = new Bebida_model();
        $busqueda = $this->request->getGet('busqueda');
        $categoriaSeleccionada = $this->request->getGet('categoria');

        // Agregamos los campos de la promoción al select y su respectivo LEFT JOIN condicionado
        $bebidaModel
            ->select('bebida.*, marca.nombre_marca, categoria.nombre_categoria, promocion.tipo_promocion, promocion.valor_promocion, promocion.fecha_inicio, promocion.fecha_fin, promocion.estado_promocion')
            ->join('marca', 'bebida.id_marca = marca.id_marca', 'left')
            ->join('categoria', 'bebida.id_categoria = categoria.id_categoria', 'left')
            ->join('promocion', 'bebida.id_bebida = promocion.id_bebida AND promocion.estado_promocion = 1', 'left'); // <-- Clave para traer solo las activas

        if ($busqueda) {
            $bebidaModel->groupStart()
                ->like('bebida.nombre_bebida', $busqueda)
                ->orLike('marca.nombre_marca', $busqueda)
                ->groupEnd();
        }

        if ($categoriaSeleccionada) {
            $bebidaModel->where('bebida.id_categoria', $categoriaSeleccionada);
        }

        $categoriaModel = new Categoria_model();

        $data = [
            'bebida'                => $bebidaModel->findAll(),
            'categoria'             => $categoriaModel->listar_categorias(),
            'busqueda'              => $busqueda,
            'categoriaSeleccionada' => $categoriaSeleccionada
        ];

        return $this->renderizarConNavbar('backend/gestionar_bebidas', $data);
    }

    // Operación: eliminar(id_bebida)
    public function eliminar($id_bebida)
    {
        if (session('id_perfil') != 1) {
            return redirect()->to('/');
        }
        $bebidaModel = new Bebida_model();
        $bebidaModel->delete($id_bebida);
        return redirect()->to('gestionar_bebidas')->with('mensaje', 'Bebida eliminada correctamente.');
    }
}
