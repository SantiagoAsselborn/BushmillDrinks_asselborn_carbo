<?php

use CodeIgniter\Router\RouteCollection;


/**
 * @var RouteCollection $routes
 */

 //Paginas Públicas
$routes->get('/', 'Home::index');
$routes->get('nosotros', 'Home::nosotros');
$routes->get('inicio', 'Home::index');
$routes->get('comercializacion', 'Home::comercializacion');
$routes->get('contacto', 'Home::contacto');
$routes->get('terminos', 'Home::terminos');
$routes->get('productos', 'Producto_controller::catalogo');


//Autenticación
$routes->get('login', 'Home::login');
$routes->post('login', 'Usuario_controller::login');

$routes->get('registro', 'Home::registro');
$routes->get('logout', 'Usuario_controller::logout');
//Formularios
$routes->post('form_consulta', 'Mensaje_controller::add_consulta');
$routes->post('form_registro', 'Usuario_controller::add_cliente');

$routes->get('agregar_bebida', 'Producto_controller::agregarBebida');
$routes->post('insertar_bebida', 'Producto_controller::registrarBebida');
$routes->get('listar_bebidas', 'Producto_controller::listarBebidas');
$routes->get('eliminar_bebida/(:num)', "Producto_controller::eliminar/$1");
$routes->get('ver_consultas', 'Mensaje_controller::verConsultas');
$routes->get('eliminar_consulta/(:num)', "Mensaje_controller::eliminarConsulta/$1");
$routes->get('detalle/(:num)', 'Producto_controller::detalle/$1');
$routes->get('marcar_leido/(:num)', 'Mensaje_controller::marcar_leido/$1');
$routes->post('registrar_marca', 'Producto_controller::registrarMarca');
$routes->post('registrar_categoria', 'Producto_controller::registrarCategoria');

// Gestión de bebidas (Admin)
$routes->get('gestionar_bebidas', 'Producto_controller::gestionarBebidas');
$routes->get('editar_bebida/(:num)', 'Producto_controller::editar/$1');
$routes->get('deshabilitar_bebida/(:num)', 'Producto_controller::deshabilitar/$1');
$routes->get('habilitar_bebida/(:num)', 'Producto_controller::habilitar/$1');
$routes->post('actualizar_bebida/(:num)', 'Producto_controller::actualizarBebida/$1');

$routes->get('usuarios', 'Usuario_controller::listarUsuarios');
$routes->get('suspender_usuario/(:num)', 'Usuario_controller::suspenderUsuario/$1');
$routes->get('habilitar_usuario/(:num)', 'Usuario_controller::habilitarUsuario/$1');
$routes->get('eliminar_usuario/(:num)', 'Usuario_controller::eliminarUsuario/$1');

$routes->get('editar_perfil', 'Usuario_controller::editar_perfil');
$routes->post('actualizar_perfil', 'Usuario_controller::actualizar_perfil');


$routes->get('verComoCliente', 'Home::verComoCliente');
$routes->get('volverAModoAdmin', 'Home::volverAModoAdmin');

//carrito de compras
$routes->get('ver_carrito', 'Carrito_controller::ver_carrito');
$routes->post('agregar_carrito', 'Carrito_controller::agregar_carrito');
$routes->get('eliminar_item/(:any)', 'Carrito_controller::eliminar_item/$1');
$routes->get('vaciar_carrito', 'Carrito_controller::vaciar_carrito');
$routes->get('ordenar_compra', 'Carrito_controller::ordenar_compra');
$routes->post('carrito/obtenerFormularioPago','Carrito_controller::obtenerFormularioPago');
$routes->post('confirmar_compra', 'Carrito_controller::confirmar_compra');
$routes->get('guardar_venta', 'Carrito_controller::guardar_venta');
$routes->post('actualizar_cantidad', 'Carrito_controller::actualizar_cantidad');
$routes->get('confirmacion_compra', 'Carrito_controller::confirmacion_compra');


$routes->get('listar_ventas','Carrito_controller::listar_ventas');
$routes->get('listar_compras', 'Carrito_controller::historial_cliente');
