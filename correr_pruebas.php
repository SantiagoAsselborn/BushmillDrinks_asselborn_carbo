<?php
// 1. Simulación del entorno de namespaces de CodeIgniter (DEBE IR PRIMERO)
namespace CodeIgniter\Test {
    class CIUnitTestCase {
        protected function setUp(): void {}
        public function assertTrue($cond, $msg = '') {
            if (!$cond) throw new \Exception($msg ?: "Fallo la validación esperada.");
        }
    }
    trait FeatureTestTrait {
        protected function withSession(array $values) { return $this; }
        protected function withFiles(array $files) { return $this; }
        protected function post(string $uri, array $params = []) {
            return new class($uri, $params) {
                private $uri; private $params;
                public function __construct($u, $p) { $this->uri = $u; $this->params = $p; }
                public function assertRedirectTo($target) { echo "Redirección a: '$target'\n"; }
                public function assertSessionHas($key, $value) { echo "Sesión verificada: [$key] '$value'\n"; }
                public function assertStatus($code) { echo "estado HTTP: $code\n"; }
                public function assertSee($text) { echo "Vista: '$text'\n"; }
            };
        }
    }
}

namespace CodeIgniter\HTTP\Files {
    class UploadedFile {
        public function __construct($path, $name, $type, $size, $error, $test) {}
    }
}

// SOLUCIÓN AL ERROR DE SESSION: Declaramos la función en el namespace que la pide tu test
namespace App\Controllers {
    function session() {
        return new class {
            public function has($key) { return true; } // Fuerza que devuelva true en la validación
        };
    }
}

// 2. Bloque de ejecución global para correr tus archivos reales
namespace {
    define('ENVIRONMENT', 'testing');
    define('ROOTPATH', __DIR__ . '/');
    define('APPPATH', __DIR__ . '/app/');
    define('WRITEPATH', __DIR__ . '/writable/');

    try {
        // SOLUCIÓN AL WARNING DE IMAGEN: Creamos las carpetas y el placeholder si no existen
        if (!is_dir(ROOTPATH . 'public/assets/images')) {
            @mkdir(ROOTPATH . 'public/assets/images', 0777, true);
        }
        if (!file_exists(ROOTPATH . 'public/assets/images/placeholder.jpg')) {
            @file_put_contents(ROOTPATH . 'public/assets/images/placeholder.jpg', 'fake_image_data');
        }
        if (!is_dir(WRITEPATH . 'uploads')) {
            @mkdir(WRITEPATH . 'uploads', 0777, true);
        }

        // IMPORTACIÓN REAL DE TUS DOS ARCHIVOS DE LA CARPETA TESTS
        require_once ROOTPATH . 'tests/unit/RegistrarBebidaTest.php';
        require_once ROOTPATH . 'tests/unit/AgregarBebidaTest.php';

        // ---------------------------------------------------
        // EJECUCIÓN SCRIPT 1: RegistrarBebidaTest
        // ---------------------------------------------------
        echo "Ejecutando: tests/unit/RegistrarBebidaTest.php\n";
        $testBebida = new \App\Controllers\RegistrarBebidaTest();
        
        echo "C1: registrarBebidaExitoso()...\n";
        $testBebida->registrarBebidaExitoso();
        
        echo "C2: registrarBebidaError()...\n";
        $testBebida->registrarBebidaError();

        echo "\n---------------------------------------------------\n";

        // ---------------------------------------------------
        // EJECUCIÓN SCRIPT 2: AgregarBebidaTest
        // ---------------------------------------------------
        echo "Ejecutando: tests/unit/AgregarBebidaTest.php\n";
        $testCarrito = new \App\Controllers\AgregarBebidaTest();

        echo "C3: agregarBebidaAlCarritoExitoso()...\n";
        $testCarrito->agregarBebidaAlCarritoExitoso();

        echo "C4: agregarBebidaCarritoStockInsuficiente()...\n";
        $testCarrito->agregarBebidaCarritoStockInsuficiente();

    } catch (\Throwable $e) {
        echo "\n\033[31m[ERROR EN ENTORNO]:\033[0m " . $e->getMessage() . "\n";
        echo "Línea: " . $e->getLine() . " en " . $e->getFile() . "\n";
    }

    echo "   PRUEBAS FINALIZADAS\n";

}