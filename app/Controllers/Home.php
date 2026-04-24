<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function verComoCliente()
    {
        if (session()->get('id_perfil') == 1) {
            session()->set('modo_cliente', true);
        }

        return redirect()->to('/');
    }

    public function volverAModoAdmin()
    {
        if (session()->get('id_perfil') == 1) {
            session()->remove('modo_cliente');
        }

        return redirect()->to('/');
    }

    public function index(): string
    {
        $bebidaModel = new \App\Models\Bebida_model();
        $productos = $bebidaModel
            ->select('bebida.*, marca.nombre_marca')
            ->join('marca', 'bebida.id_marca = marca.id_marca')
            ->where('bebida.estado_bebida', 1)
            ->findAll();

        return $this->renderizarConNavbar('nueva_plantilla', ['bebida' => $productos]);
    }


    public function nosotros(): string
    {
        return $this->renderizarConNavbar('quienesSomos');
    }

    public function inicio(): string
    {
        $bebidaModel = new \App\Models\Bebida_model();
        $productos = $bebidaModel
            ->select('bebida.*, marca.nombre_marca')
            ->join('marca', 'bebida.id_marca = marca.id_marca')
            ->where('bebida.estado_bebida', 1)
            ->findAll();

        return $this->renderizarConNavbar('nueva_plantilla', ['bebida' => $productos]);
    }


    public function comercializacion(): string
    {
        return $this->renderizarConNavbar('comercializacion');
    }

    public function contacto(): string
    {
        return $this->renderizarConNavbar('contacto');
    }

    public function terminos(): string
    {
        return $this->renderizarConNavbar('terminos');
    }

    public function login(): string
    {
        return $this->renderizarConNavbar('login');
    }

    public function registro(): string
    {
        return $this->renderizarConNavbar('registro');
    }

    public function productos(): string
    {
        return $this->renderizarConNavbar('catalogo');
    }
}
