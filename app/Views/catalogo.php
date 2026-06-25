<?php
function crearIdCategoria($nombre) {
    $id = strtolower($nombre);
    // Reemplaza caracteres no alfanuméricos por guiones
    $id = preg_replace('/[^a-z0-9]+/', '-', $id);
    return trim($id, '-');
}

$agrupados = [];
foreach ($bebida as $b) { // Usamos $b temporal en el bucle para no pisar la variable global
    $agrupados[$b['nombre_categoria']][] = $b;
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
                        <?php foreach ($items as $bebida): 
                            // Determinamos de antemano si el producto posee descuento vigente
                            $tienePromo = (!empty($bebida['estado_promocion']) && $bebida['estado_promocion'] == 1);
                            $precioVenta = $bebida['precio_bebida'];
                            
                            if ($tienePromo && $bebida['tipo_promocion'] === 'descuento') {
                                $precioVenta = $bebida['precio_bebida'] * (1 - ($bebida['valor_promocion'] / 100));
                            }
                        ?>
                        <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
                            <div class="card h-100 border-0 shadow-sm rounded-4 position-relative">
                                
                                <?php if ($tienePromo): ?>
                                    <span class="badge bg-danger position-absolute top-0 end-0 m-3 py-2 px-2 rounded-3 shadow-sm" style="z-index: 2; font-size: 0.85rem;">
                                        <i class="bi bi-lightning-fill"></i> <?= number_format($bebida['valor_promocion'], 0) ?>% OFF
                                    </span>
                                <?php endif; ?>

                                <img src="<?= base_url('assets/upload/' . ($bebida['imagen_bebida'] ?: 'default.png')) ?>" class="card-img-top rounded-top-4" alt="<?= esc($bebida['nombre_bebida']) ?>" style="object-fit: cover; height: 200px;">
                                
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title text-dark fw-semibold mb-1"><?= esc($bebida['nombre_bebida']) ?></h5>
                                    <p class="text-muted small mb-3" style="line-height: 1.2;"><?= esc(substr($bebida['descripcion_bebida'], 0, 50)) ?>...</p>

                                    <div class="mb-3">
                                        <?php if ($tienePromo): ?>
                                            <span class="text-muted text-decoration-line-through small d-block">
                                                $<?= number_format($bebida['precio_bebida'], 2, ',', '.') ?>
                                            </span>
                                            <span class="text-danger fw-bold fs-4">
                                                $<?= number_format($precioVenta, 2, ',', '.') ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="fw-bold fs-4 text-dark">
                                                $<?= number_format($bebida['precio_bebida'], 2, ',', '.') ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>

                                    <div class="mt-auto d-grid gap-2">
                                        <a href="<?= base_url('detalle/' . $bebida['id_bebida']) ?>" class="btn btn-outline-dark btn-sm">Ver detalle</a>

                                        <?php if(session('id_perfil') == 2): ?>
                                            <?= form_open('agregar_carrito') ?>
                                                <?= form_hidden('id', $bebida['id_bebida']) ?>
                                                <?= form_hidden('nombre', $bebida['nombre_bebida']) ?>
                                                <?= form_hidden('precio', number_format($precioVenta, 2, '.', '')) ?>

                                                <button type="submit" class="btn btn-success btn-sm w-100">
                                                    <i class="bi bi-cart-plus"></i> Agregar
                                                </button>
                                            <?= form_close() ?>
                                        <?php elseif(!session('id_perfil')): ?>
                                            <span class="d-inline-block" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="¡Inicia sesión para comprar!">
                                                <button class="btn btn-secondary btn-sm w-100" type="button" disabled>Agregar</button>
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
        <?php $indice++; endforeach; ?>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Inicializar Popovers de Bootstrap
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



