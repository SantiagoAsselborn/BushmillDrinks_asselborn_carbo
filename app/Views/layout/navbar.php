<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inicio</title>

    <link rel="stylesheet" href="<?= base_url('assets/css/estilos.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
</head>

<body>

<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= base_url('inicio') ?>">
            <img src="<?= base_url('assets/img/Logo.png') ?>" alt="Logo" width="50" height="50">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="<?= base_url('nosotros') ?>">Nosotros</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= base_url('inicio#Ofertas') ?>">Ofertas</a></li>
                <li class="nav-item dropdown d-flex align-items-center">
                    <a class="nav-link" href="<?= base_url('productos') ?>">Productos</a>
                    <a class="nav-link dropdown-toggle" href="#" id="categoriasDropdown" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false"></a>
                    <ul class="dropdown-menu" aria-labelledby="categoriasDropdown">
                        <?php if (isset($categorias) && is_array($categorias)): ?>
                            <?php foreach ($categorias as $categoria): 
                                $nombre = $categoria['nombre_categoria'] ?? '';
                                $id_categoria = strtolower($nombre);
                                $id_categoria = preg_replace('/[^a-z0-9]+/', '-', $id_categoria);
                                $id_categoria = trim($id_categoria, '-');
                            ?>
                            <li>
                                <a class="dropdown-item" href="<?= base_url('productos#' . $id_categoria) ?>">
                                    <?= esc($nombre) ?>
                                </a>
                            </li>
                            <?php endforeach; ?>
                            <?php else: ?>
                                <li><span class="dropdown-item text-muted">Sin categorías</span></li>
                        <?php endif; ?>
                    </ul>
                </li>
                <li class="nav-item"><a class="nav-link" href="<?= base_url('comercializacion') ?>">Comercialización</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= base_url('contacto') ?>">Contacto</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= base_url('terminos') ?>">Términos y Condiciones</a></li>
            </ul>
            <div class="d-flex">
                <a href="<?= base_url('login') ?>" class="btn btn-outline-secondary me-2"
                   style="border-color: black; color: black;">Iniciar sesión</a>
                <a href="<?= base_url('registro') ?>" class="btn btn-dark">Registrarse</a>
            </div>
        </div>
    </div>
</nav>

<!-- Cartel de Promociones -->
<div class="promo-banner">
    <div class="promo-text">
        🚚 Envío gratis en pedidos mayores a $20.000 |
        🍻 2x1 en cervezas artesanales esta semana |
        🥃 10% de descuento abonando en efectivo o por transferencia
    </div>
</div>
