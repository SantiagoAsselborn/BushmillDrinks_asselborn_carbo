<?php

namespace App\Controllers;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Files\File;
use CodeIgniter\HTTP\Files\UploadedFile;

class RegistrarBebidaTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /**Registrar bebida (Caso exitoso)*/
    public function registrarBebidaExitoso()
    {
        $sesionSimulada = ['id_perfil' => 1];
        $rutaImagenDummy = WRITEPATH . 'uploads/test_image.jpg';
        copy(ROOTPATH . 'public/assets/images/placeholder.jpg', $rutaImagenDummy); 
        
        $imagenMokeada = new UploadedFile(
            $rutaImagenDummy,
            'greenlabel.jpg',
            'image/jpeg',
            filesize($rutaImagenDummy),
            0, 
            true 
        );

        $datosPost = [
            'nombre_bebida'      => 'Green Label',
            'descripcion_bebida' => 'blended malt añejado durante un mínimo de 15 años',
            'precio_bebida'      => 120000,
            'stock_bebida'       => 15,
            'volumen_bebida'     => 750,
            'grado_bebida'       => 42,
            'id_marca'           => 11,
            'id_categoria'       => 5
        ];

        $resultado = $this->withSession($sesionSimulada)
                          ->withFiles(['imagen_bebida' => $imagenMokeada])
                          ->post('bebidas/registrarBebida', $datosPost);

        $resultado->assertRedirectTo('gestionar_bebidas');
        $resultado->assertSessionHas('mensaje', 'Bebida registrada correctamente.');
    }

    /**Registrar bebida (Caso de error por preio negativo)*/
    public function registrarBebidaError()
    {
        $sesionSimulada = ['id_perfil' => 1];

        // Ponemos el precio con valor negativo, invalidando las precondiciones
        $datosPostConError = [
            'nombre_bebida'      => 'Gold Label',
            'descripcion_bebida' => 'Exclusivo blended scotch whisky escocés, reconocido por su perfil lujoso, suave y meloso.',
            'precio_bebida'      => -112, 
            'stock_bebida'       => 20,
            'volumen_bebida'     => 750,
            'grado_bebida'       => 40,
            'id_marca'           => 11,
            'id_categoria'       => 5
        ];

        $resultado = $this->withSession($sesionSimulada)
                          ->post('bebidas/registrarBebida', $datosPostConError);

        $resultado->assertStatus(200); 
        $resultado->assertSee('backend/registrar_bebida'); 
        $this->assertTrue(session()->has('validation'), "Debe contener los errores de validación arrojados por el validador.");
    }
}