<?php

namespace App\Controllers;

use App\Models\Mensajes_model;
use App\Models\Usuario_model;
use App\Models\Categoria_model;

class Usuario_controller extends BaseController
{

    public function index()
    {
        $bebidaModel = new \App\Models\Bebida_model();
        $bebidas = $bebidaModel
            ->select('bebida.*, marca.nombre_marca')
            ->join('marca', 'bebida.id_marca = marca.id_marca')
            ->join('promocion', 'bebida.id_bebida = promocion.id_bebida', 'left')
            ->where('bebida.estado_bebida', 1)
            ->where('promocion.estado_promocion', 1)
            ->findAll();
        $this->renderizarConNavbar('nueva_plantilla', ['bebidas' => $bebidas]);
    }


    public function add_cliente()
    {
        $validation = \Config\Services::validation();
        $request = \Config\Services::request();

        $validation->setRules(
            [
                'nombre_usuario' => 'required|max_length[50]|regex_match[/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/]',
                'apellido_usuario' => 'required|max_length[50]|regex_match[/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/]',
                'usuario' => 'required|max_length[20]|is_unique[usuario.usuario]',
                'email_usuario' => 'required|valid_email|max_length[100]|is_unique[usuario.email_usuario]',
                'pass_usuario' => 'required|min_length[5]|max_length[100]',
            ],
            [   // Errores
                'nombre_usuario' => [
                    'required' => 'El nombre es requerido',
                    'regex_match'  => 'El nombre solo puede contener letras y espacios',
                ],

                'apellido_usuario' => [
                    'required' => 'El apellido es requerido',
                    'regex_match'  => 'El apellido solo puede contener letras y espacios',
                ],

                'usuario' => [
                    'required' => 'El usuario es requerido',
                    'max_length'  => 'El usuario no puede contener mas de 20 caracteres',
                    'is_unique' => 'El nombre de usuario no esta disponible',
                ],

                'email_usuario' => [
                    'required' => 'El correo electrónico es obligatorio',
                    'valid_email' => 'La dirección de correo debe ser válida',
                    'is_unique' => 'Ya existe una cuenta con este correo electronico'
                ],

                'pass_usuario'   => [
                    "required"      => 'La contraseña es requerida',
                    "max_length"    => 'La contraseña no debe superar los 100 caracteres',
                    "min_length" => 'La contraseña debe tener al menos 5 caracteres',
                ],
            ]
        );

        if ($validation->withRequest($request)->run() ){
            $data = [
                    'nombre_usuario' => $this->request->getPost('nombre_usuario'),
                    'apellido_usuario' => $this->request->getPost('apellido_usuario'),
                    'usuario' => $this->request->getPost('usuario'),
                    'email_usuario' => $this->request->getPost('email_usuario'),
                    'pass_usuario' => password_hash($this->request->getPost('pass_usuario'), PASSWORD_DEFAULT),
                    'id_perfil' => $this->request->getPost('id_perfil'),
                    'baja' => '0'
            ];

                    $usuarioModel = new Usuario_model();
                    $usuarioModel->insert($data);

                    session()->set([
                        'id_usuario'            => $usuarioModel->getInsertID(),
                        'nombre_usuario'        => $data['nombre_usuario'],
                        'usuario'               => $data['usuario'],
                        'id_perfil'             => $data['id_perfil'],
                        'logueado'              => true
                    ]);
                    return redirect()->to('/');
                        
        }
        else {
            $data['titulo'] = 'Contacto';
            $data['validation'] = $validation; // PASÁS EL OBJETO, no el array
            return view('layout/navbar', $data) . view('registro', $data) . view('layout/footer');
        }

    }

    public function login()
    {
        $validation = \Config\Services::validation();
        $request = \Config\Services::request();

        $validation->setRules([
            'email_usuario' => 'required|valid_email',
            'pass_usuario' => 'required'
        ], [
            'email_usuario' => [
                'required' => 'El correo electrónico es obligatorio',
                'valid_email' => 'Debe ingresar un correo válido'
            ],
            'pass_usuario' => [
                'required' => 'La contraseña es obligatoria'
            ]
        ]);

        if (!$validation->withRequest($request)->run()) {
            return view('layout/navbar').view('login', [
                'validation' => $validation
            ]).view('layout/footer');
        }

        $email = $this->request->getPost('email_usuario');
        $password = $this->request->getPost('pass_usuario');

        $usuarioModel = new \App\Models\Usuario_model();
        $usuario = $usuarioModel->where('email_usuario', $email)->first();

        if ($usuario && password_verify($password, $usuario['pass_usuario'])) {
            if ($usuario['baja'] === '1') {
                return redirect()->to('/login')->with('error', 'Su cuenta está suspendida. Contacte al administrador.');
            }

            session()->set([
                'id_usuario'         => $usuario['id_usuario'],
                'nombre_usuario'     => $usuario['nombre_usuario'],
                'usuario'            => $usuario['usuario'],
                'id_perfil'          => $usuario['id_perfil'],
                'logueado'           => true
            ]);

            return redirect()->to('/');
        } else {
            return redirect()->to('/login')->with('error', 'Correo o contraseña inválidos.');
        }
    }



    public function logout()
    {
        session()->destroy(); 
        return redirect()->to('/'); 
    }

    public function edit($id)
    {
        $usuariosModel = new Usuario_model();
        $data['usuario'] = $usuariosModel->find($id);
        return view('usuarios/edit', $data);
    }

    public function update($id)
    {
        $usuariosModel = new Usuario_model();
        $data = [
            'nombre_usuario' => $this->request->getPost('nombre_usuario'),
            'apellido_usuario' => $this->request->getPost('apellido_usuario'),
            'usuario' => $this->request->getPost('usuario'),
            'email_usuario' => $this->request->getPost('email_usuario'),
            'id_perfil' => $this->request->getPost('id_perfil'),
            'baja' => $this->request->getPost('baja')
        ];
        $usuariosModel->update($id, $data);
        return view('layout/navbarCliente', $data).view('nueva_plantilla').view('layout/footer');
    }

    public function delete($id)
    {
        $usuariosModel = new Usuario_model();
        $usuariosModel->delete($id);
        return redirect()->to('/usuarios');
    }

    public function add_consulta()
    {
        $validation = \Config\Services::validation();
        $request = \Config\Services::request();
        $validation->setRules(
            [
                'nombre_mensaje' => 'required|max_length[100]',
                'mail_mensaje' => 'required|valid_email|max_length[100]',
                'telefono_mensaje' => 'required|max_length[20]',
                'consulta_mensaje' => 'required|max_length[250]|min_length[10]',
            ],
            [   // Errores
                'nombre_mensaje' => [
                    'required' => 'El nombre es requerido',
                ],

                'mail_mensaje' => [
                    'required' => 'El correo electrónico es obligatorio',
                    'valid_email' => 'La dirección de correo debe ser válida'
                ],

                'telefono_mensaje'   => [
                    "required"      => 'El telefono es obligatorio',
                    "max_length"    => 'El telefono no debe superar los 50 caracteres'
                    ],

                'consulta_mensaje' => [
                    'required' => 'La consulta es requerida',
                    'min_length' =>'La consulta debe tener como mínimo 10 caracteres',
                    'max_length'    => 'La consulta debe tener como máximo 250 caracteres',
                    ],
            ]
        );

        if ($validation->withRequest($request)->run() ){
            $data = [
                'nombre_mensaje' => $request->getPost('nombre_mensaje'),
                'mail_mensaje' => $request->getPost('mail_mensaje'),
                'telefono_mensaje' => $request->getPost('telefono_mensaje'),
                'consulta_mensaje' => $request->getPost('consulta_mensaje') 
                    ];

                    $mensajesModel = new Mensajes_model();
                    $mensajesModel->insert($data);

                    return redirect()->route('contacto')->with('mensaje_exito', 'Su consulta se envió exitosamente!');
                        
        }
        else{
            $data['titulo'] = 'Contacto';
            $data['validation'] = $validation->getErrors();
            return view('layout/navbar', $data).view('contacto').view('layout/footer');
        }
    }

    public function listarUsuarios()
    {
        if (session('id_perfil') != 1) {
            return redirect()->to('/');
        }
        $usuarioModel = new Usuario_model();
        $perfil = $this->request->getGet('perfil');
        $email = $this->request->getGet('email');

        $query = $usuarioModel;

        if (!empty($perfil)) {
            $query = $query->where('id_perfil', $perfil);
        }

        if (!empty($email)) {
            $query = $query->like('email_usuario', $email);
        }

        $data['usuarios'] = $query->findAll();

        return view('layout/navbarAdmin')
            . view('backend/listar_usuarios', $data)
            . view('layout/footer');
    }


    public function suspenderUsuario($id)
    {
        if (session('id_perfil') != 1) {
            return redirect()->to('/');
        }
        $usuarioModel = new Usuario_model();
        $usuarioModel->update($id, ['baja' => '1']);
        return redirect()->to('/usuarios');
    }

    public function habilitarUsuario($id)
    {
        if (session('id_perfil') != 1) {
            return redirect()->to('/');
        }
        $usuarioModel = new Usuario_model();
        $usuarioModel->update($id, ['baja' => '0']);
        return redirect()->to('/usuarios');
    }

    public function cambiarTipo($id)
    {
        if (session('id_perfil') != 1) {
            return redirect()->to('/');
        }
        $usuarioModel = new Usuario_model();
        $usuario = $usuarioModel->find($id);
    
        // Cambiar entre admin (1) y usuario (2)
        $nuevoTipo = ($usuario['id_perfil'] == 1) ? 2 : 1;
        $usuarioModel->update($id, ['id_perfil' => $nuevoTipo]);
        return redirect()->to('/usuarios');
    }

    public function eliminarUsuario($id)
    {
        if (session('id_perfil') != 1) {
            return redirect()->to('/');
        }
        // Evita que un admin se elimine a sí mismo
        if (session('id_usuario') == $id) {
            return redirect()->to('/usuarios')->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        $usuarioModel = new Usuario_model();
        $usuarioModel->delete($id);
        return redirect()->to('/usuarios');
    }

    public function editar_perfil()
    {
        $usuarioModel = new \App\Models\Usuario_model();
        $id = session('id_usuario');
        $usuario = $usuarioModel->find($id);

        return view('layout/navbarCliente')
            . view('backend/editar_perfil', ['usuario' => $usuario])
            . view('layout/footer');
    }

    public function actualizar_perfil()
    {
        $session = session();
        $request = \Config\Services::request();
        $validation = \Config\Services::validation();
        $usuarioModel = new \App\Models\Usuario_model();

        $id_usuario         = $request->getPost('id_usuario');
        $nombre_usuario     = trim($request->getPost('nombre_usuario'));
        $apellido_usuario   = trim($request->getPost('apellido_usuario'));
        $usuario            = trim($request->getPost('usuario'));
        $email_usuario      = trim($request->getPost('email_usuario'));
        $pass_usuario       = $request->getPost('pass_usuario');

        // Reglas de validación
        $validation->setRules([
            'nombre_usuario'   => 'required|regex_match[/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/]',
            'apellido_usuario' => 'required|regex_match[/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/]',
            'usuario'  => 'required|min_length[3]|max_length[30]',
            'email_usuario'    => 'required|valid_email',
            'pass_usuario'     => 'permit_empty|min_length[5]|max_length[100]',
        ],
        [   // Mensajes personalizados
            'nombre_usuario' => [
                'required' => 'El nombre es obligatorio',
                'regex_match' => 'El nombre solo debe contener letras y espacios',
            ],
            'apellido_usuario' => [
                'required' => 'El apellido es obligatorio',
                'regex_match' => 'El apellido solo debe contener letras y espacios',
            ],
            'usuario' => [
                'required'    => 'El nombre de usuario es obligatorio',
                'min_length'  => 'El nombre de usuario no debe ser menor a 3 caracteres',
                'max_length'  => 'El nombre de usuario no debe superar los 30 caracteres'
            ],
            'email_usuario' => [
                'required'     => 'El correo electrónico es obligatorio',
                'valid_email'  => 'La dirección de correo debe ser válida'
            ],
            'pass_usuario' => [
                'min_length'   => 'La contraseña debe tener al menos 5 caracteres',
                'max_length'   => 'La contraseña no debe superar los 100 caracteres'
            ],
        ]);

        if ($validation->withRequest($request)->run()) {

            // Verificar si el usuario ya existe
            $existeUsuario = $usuarioModel->where('usuario', $usuario)
                                          ->where('id_usuario !=', $id_usuario)
                                          ->first();
            if ($existeUsuario) {
                $data['error'] = 'El nombre de usuario ya está en uso.';
                $data['validation'] = [];
                $data['usuario'] = $usuarioModel->find($id_usuario);
                return view('layout/navbarCliente', $data)
                    .view('backend/editar_perfil', $data)
                    .view('layout/footer');
            }

            $existeEmail = $usuarioModel->where('email_usuario', $email_usuario)
                                        ->where('id_usuario !=', $id_usuario)
                                        ->first();
            if ($existeEmail) {
                $data['error'] = 'El correo electrónico ya está registrado.';
                $data['validation'] = [];
                $data['usuario'] = $usuarioModel->find($id_usuario);
                return view('layout/navbarCliente', $data)
                    .view('backend/editar_perfil', $data)
                    .view('layout/footer');
            }

            // Preparar datos
            $datosActualizar = [
                'nombre_usuario' => $nombre_usuario,
                'apellido_usuario' => $apellido_usuario,
                'usuario' => $usuario,
                'email_usuario' => $email_usuario,
            ];

            if (!empty($pass_usuario)) {
                $datosActualizar['pass_usuario'] = password_hash($pass_usuario, PASSWORD_DEFAULT);
            }

            $usuarioModel->update($id_usuario, $datosActualizar);
            $session->set([
                'nombre_usuario' => $nombre_usuario,
                'apellido_usuario' => $apellido_usuario,
                'usuario' => $usuario,
                'email_usuario' => $email_usuario,
                ]);
            return redirect()->to('editar_perfil')->with('success', 'Perfil actualizado correctamente.');

        } else {
            $data['validation'] = $validation->getErrors();
            $data['usuario'] = $usuarioModel->find($id_usuario);
            $data['categoria'] = (new Categoria_model())->orderBy('nombre_categoria', 'ASC')->findAll();
            return view('layout/navbarCliente', $data)
                .view('backend/editar_perfil', $data)
                .view('layout/footer');
        }
    }

}