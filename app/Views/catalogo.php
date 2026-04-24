<?php
function crearIdCategoria($nombre) {
    $id = strtolower($nombre);
    // Reemplaza caracteres no alfanuméricos por guiones
    $id = preg_replace('/[^a-z0-9]+/', '-', $id);
    return trim($id, '-');
}

$agrupados = [];
foreach ($bebida as $bebida) {
    // Usamos el nombre de la categoría para agrupar
    $agrupados[$bebida['nombre_categoria']][] = $bebida;
}
?>

<div class="container mt-5">
    <h2 class="mb-4 text-center fw-bold">Nuestras Bebidas</h2>

    <?php if (session()->getFlashdata('error_stock')): ?>
        <div class="alert alert-danger text-center">
            <?= session()->getFlashdata('error_stock') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('mensaje')): ?>
        <div class="alert alert-success text-center">
            <?= session()->getFlashdata('mensaje') ?>
        </div>
    <?php endif; ?>

    <div class="accordion" id="accordionCategorias">
        <?php
        $indice = 0;
        foreach ($agrupados as $categoria => $items):
            $id_categoria = crearIdCategoria($categoria);
            $id_collapse = 'collapse-' . $id_categoria;
        ?>
        <div class="accordion-item mb-3 border-0 shadow-sm" id="<?= $id_categoria ?>">
            <h2 class="accordion-header" id="heading<?= $indice ?>">
                <button class="accordion-button <?= $indice > 0 ? 'collapsed' : '' ?> fw-semibold fs-5" type="button" data-bs-toggle="collapse" data-bs-target="#<?= $id_collapse ?>" aria-expanded="<?= $indice === 0 ? 'true' : 'false' ?>" aria-controls="<?= $id_collapse ?>">
                    <?= esc($categoria) ?>
                </button>
            </h2>
            <div id="<?= $id_collapse ?>" class="accordion-collapse collapse <?= $indice === 0 ? 'show' : '' ?>" aria-labelledby="heading<?= $indice ?>" data-bs-parent="#accordionCategorias">
                <div class="accordion-body bg-light rounded-bottom">
                    <div class="row">
                        <div class="row">
    <?php foreach ($items as $bebida): ?>
        <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
            <div class="card h-100 border-0 shadow-sm rounded-4">
                <img src="<?= base_url('assets/upload/' . $bebida['imagen_bebida']) ?>" class="card-img-top rounded-top-4" alt="<?= esc($bebida['nombre_bebida']) ?>" style="object-fit: cover; height: 200px;">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title text-dark"><?= esc($bebida['nombre_bebida']) ?></h5>

                    <div class="mb-3">
                        <span class="fw-bold fs-5">$<?= number_format($bebida['precio_bebida'], 2, ',', '.') ?></span>
                    </div>

                    <div class="mt-auto d-grid gap-2">
                        <a href="<?= base_url('detalle/' . $bebida['id_bebida']) ?>" class="btn btn-outline-dark">Ver detalle</a>

                        <?php if(session('id_perfil') == 2): ?>
                            <?= form_open('agregar_carrito') ?>
                                <?= form_hidden('id', $bebida['id_bebida']) ?>
                                <?= form_hidden('nombre', $bebida['nombre_bebida']) ?>
                                <?= form_hidden('precio', $bebida['precio_bebida']) ?>

                                <button type="submit" class="btn btn-success w-100">
                                    <i class="bi bi-cart-plus"></i> Agregar
                                </button>
                            <?= form_close() ?>
                        <?php elseif(!session('id_perfil')): ?>
                            <span class="d-inline-block" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="¡Inicia sesión para comprar!">
                                <button class="btn btn-secondary w-100" type="button" disabled>Agregar</button>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
                    </div>
                </div>
            </div>
        </div>
        <?php $indice++; endforeach; ?>
    </div>
</div>

<script>
    // Inicializar Popovers de Bootstrap
    document.addEventListener('DOMContentLoaded', function () {
        const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl);
        });

        // Manejo de scroll automático por Hash (#categoria)
        const hash = window.location.hash.substring(1);
        if (hash) {
            const targetItem = document.getElementById(hash);
            const collapseEl = document.getElementById('collapse-' + hash);
            if (collapseEl && targetItem) {
                const bCollapse = new bootstrap.Collapse(collapseEl, { show: true });
                setTimeout(() => {
                    targetItem.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, 500);
            }
        }
    });
</script>



