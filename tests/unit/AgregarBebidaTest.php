<?php

namespace App\Controllers;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

class AgregarBebidaTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /** Bebida existente y stock válido (caso de éxito)*/
    public function agregarBebidaAlCarritoExitoso()
    {
        // Simulamos un cliente
        $sesionCliente = ['id_usuario' => 4, 'id_perfil' => 2];
        $datosPost = [
            'id_bebida' => 1, // "Fernet Branca"
            'cantidad'  => 2
        ];

        $resultado = $this->withSession($sesionCliente)
                          ->post('carrito/agregar', $datosPost);

        // Resultado esperado
        $resultado->assertSessionHas('mensaje', 'Bebida agregada correctamente');
    }

    /**Bebida existente y stock insuficiente (Fallo) */
    public function agregarBebidaCarritoStockInsuficiente()
    {
        $sesionCliente = ['id_usuario' => 4, 'id_perfil' => 2];
        $datosPost = [
            'id_bebida' => 2, // Six Pack Miller
            'cantidad'  => 50
        ];

        $resultado = $this->withSession($sesionCliente)
                          ->post('carrito/agregar', $datosPost);

        // Resultado Esperado
        $resultado->assertSessionHas('error', 'Stock insuficiente');
    }
}