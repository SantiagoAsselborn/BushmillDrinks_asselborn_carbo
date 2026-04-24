<?php

namespace App\Controllers;

use App\Models\Mensajes_model;

class Mensaje_controller extends BaseController
{   

    public function index()
    {
        $productoModel = new \App\Models\Bebida_model();
        $productos = $productoModel
            ->select('bebida.*, marca.nombre_marca')
            ->join('marca', 'bebida.id_marca = marca.id_marca')
            ->where('bebida.estado_bebida', 1)
            ->where('bebida.oferta_bebida', 1)
            ->findAll();
        $this->renderizarConNavbar('nueva_plantilla', ['productos' => $productos]);
    }

    public function add_consulta()
    {
        // Cargar modelo Mensajes_model
        $mensajeModel = new \App\Models\Mensajes_model();
        $validation = \Config\Services::validation();

        $validation->setRules([
            'nombre_mensaje' => 'required|max_length[100]|regex_match[/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/]',
            'mail_mensaje' => 'required|valid_email|max_length[100]',
            'telefono_mensaje' => 'required|regex_match[/^\+?[0-9\s\-\(\)]{7,20}$/]',
            'consulta_mensaje' => 'required|max_length[254]|min_length[10]',
        ],
        [   // Errores
            'nombre_mensaje' => [
                    'required' => 'El nombre es obligatorio',
                    'regex_match'  => 'El nombre solo puede contener letras y espacios',
            ],
            'mail_mensaje' => [
                'required' => 'El correo electrónico es obligatorio',
                'valid_email' => 'La dirección de correo debe ser válida',
            ],
            'telefono_mensaje' => [
                'required' => 'El telefono es obligatorio',
                'regex_match' => 'El teléfono solo puede contener números, espacios, paréntesis, guiones y un símbolo "+" opcional.',
            ],
            'consulta_mensaje' => [
                'required' => 'El mensaje es obligatorio',
                'min_length' => 'El mensaje es muy corto',
            ],
        ],
        );
        
        // Validar
        if (!$validation->withRequest($this->request)->run()) {
            // Devolver la vista con los errores
            return view('layout/navbar').view('contacto', [
                'validation' => $validation]).view('layout/footer');
        }


        // Obtener datos enviados por POST
        $data = [
            'nombre_mensaje'   => $this->request->getPost('nombre_mensaje'),
            'mail_mensaje'     => $this->request->getPost('mail_mensaje'),
            'telefono_mensaje' => $this->request->getPost('telefono_mensaje'),
            'consulta_mensaje' => $this->request->getPost('consulta_mensaje'),
            'estado_mensaje' => 0,
        ];

        // Guardar en la base de datos
        $mensajeModel->insert($data);

        // Redirigir o mostrar mensaje
        return redirect()->to('/contacto')->with('mensaje', 'Consulta enviada correctamente');
    }

    public function verConsultas()
        {
            if (session('id_perfil') != 1) return redirect()->to('/');
            $mensajeModel = new Mensajes_model();
            $mensajes = $mensajeModel->findAll();

            return view('layout/navbarAdmin')
                  .view('backend/ver_consultas', ['mensajes' => $mensajes])
                  .view('layout/footer');
        }

    public function eliminarConsulta($id)
    {
        $mensajesModel = new Mensajes_model();
        $mensajesModel->delete($id);
        return redirect()->to('backend/ver_consultas')->with('mensaje', 'Consulta eliminada exitosamente.');
    }

   public function marcar_leido($id) 
    {
        if (session('id_perfil') != 1) return redirect()->to('/');
    
        $mensajeModel = new \App\Models\Mensajes_model();
    
        // Depuración: Verificar si el registro existe
        if (!$mensajeModel->find($id)) {
            return redirect()->to('ver_consultas')->with('error', 'Consulta no encontrada');
        }

        // Obtener estado actual y alternarlo
        $estadoActual = $mensajeModel->where('id_mensaje', $id)->first()['estado_mensaje'];
        $nuevoEstado = $estadoActual ? 0 : 1;

        // Actualizar con protección desactivada temporalmente
        $mensajeModel->protect(false)->update($id, ['estado_mensaje' => $nuevoEstado]);
    
        // Verificar si se actualizó
        $mensajeActualizado = $mensajeModel->find($id);
        if ($mensajeActualizado['estado_mensaje'] != $nuevoEstado) {
            log_message('error', "Fallo al actualizar mensaje ID: {$id}");
        }

        return redirect()->to('ver_consultas')->with('mensaje', 'Estado de lectura actualizado');
    }
}
