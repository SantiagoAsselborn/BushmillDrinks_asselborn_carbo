<div class="container mt-5 mb-5">
    <div class="row g-5 align-items-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <img src="<?= base_url('assets/upload/' . $bebida['imagen_bebida']) ?>" 
                     class="img-fluid rounded-4" 
                     alt="<?= esc($bebida['nombre_bebida']) ?>">
            </div>
        </div>

        <div class="col-md-6">
            <?php if (session()->getFlashdata('error_stock')): ?>
                <div class="alert alert-danger text-center"><?= session()->getFlashdata('error_stock') ?></div>
            <?php endif; ?>

            <h2 class="fw-bold mb-3"><?= esc($bebida['nombre_bebida']) ?></h2>
            
            <ul class="list-group list-group-flush mb-4">
                <li class="list-group-item"><strong>Descripción:</strong> <?= esc($bebida['descripcion_bebida']) ?></li>
                <li class="list-group-item"><strong>Precio:</strong> $<?= esc($bebida['precio_bebida']) ?></li>
                <li class="list-group-item"><strong>Volumen:</strong> <?= esc($bebida['volumen_bebida']) ?>ml</li>
                <li class="list-group-item"><strong>Graduación alcohólica:</strong> <?= esc($bebida['grado_bebida']) ?>%</li>
                <li class="list-group-item"><strong>Stock:</strong> <?= $bebida['stock_bebida'] > 0 ? esc($bebida['stock_bebida']) : '<span class="text-danger fw-bold">Sin stock</span>' ?></li>
            </ul>

            <?php if (session('id_perfil') == 2): ?>
                <?php 
                    $precio_final = ($bebida['precio_bebida'] && $bebida['precio_bebida'] > 0) 
                        ? $bebida['precio_bebida'] 
                        : $bebida['precio_bebida'];
                ?>
                <?= form_open('agregar_carrito') ?>
                    <?= csrf_field() ?>
                    <?= form_hidden('id', $bebida['id_bebida']) ?>
                    <?= form_hidden('nombre', $bebida['nombre_bebida']) ?>
                    <?= form_hidden('precio', $precio_final) ?>
                    
                    <button type="submit" class="btn btn-success btn-lg w-100" <?= $bebida['stock_bebida'] <= 0 ? 'disabled' : '' ?>>
                        <i class="bi bi-cart-plus"></i> Agregar al carrito
                    </button>
                <?= form_close() ?>
            <?php elseif (!session('id_perfil')): ?>
                <span class="d-inline-block w-100" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="¡Inicia sesión para realizar tu compra!">
                    <button class="btn btn-secondary btn-lg w-100" type="button" disabled>Agregar al carrito</button>
                </span>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    // Inicializar popovers para la versión sin login
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(el => new bootstrap.Popover(el));
</script>
