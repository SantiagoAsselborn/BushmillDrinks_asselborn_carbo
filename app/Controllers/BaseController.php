<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\Categoria_model;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = [];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = service('session');
    }

    protected function renderizarConNavbar(string $vista, array $datos = []): string
    {
        $navbar = $this->obtenerNavbar();

        // Combinar $datos con categorías para que estén disponibles en TODAS las vistas
        $datosCombinados = array_merge(
            ['categorias'=> $this->obtenerCategoriasConProductosActivos()],
            $datos
        );

    return view($navbar, $datosCombinados)
        . view($vista, $datosCombinados)
        . view('layout/footer');
    }



    protected function obtenerNavbar(): string
    {
        $perfil = session()->get('id_perfil');

        if (session()->get('logueado')) {
            if ($perfil == 1) {
                if (session()->get('modo_cliente')) {
                    return 'layout/navbarAdminVisitante';
                }
                return 'layout/navbarAdmin';
            } elseif ($perfil == 2) {
                return 'layout/navbarCliente';
            }
        }

        return 'layout/navbar';
    }

    protected function obtenerCategoriasConProductosActivos(): array
    {
        $productoModel = new \App\Models\Bebida_model();

        $resultados = $productoModel
            ->select('categoria.id_categoria, categoria.nombre_categoria')
            ->join('categoria', 'categoria.id_categoria = bebida.id_categoria')
            ->where('bebida.estado_bebida', 1)
            ->groupBy('categoria.id_categoria, categoria.nombre_categoria')
            ->orderBy('categoria.nombre_categoria', 'ASC') // ✅ Ordena alfabéticamente
            ->findAll();

        return $resultados;
    }
}
