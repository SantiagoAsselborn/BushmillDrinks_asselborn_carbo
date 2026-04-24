<?php $cart = \Config\Services::cart(); ?>

<div class="container mt-5">
    <h1 class="text-center mb-4">🛒 Carrito de Compras</h1>
    
    <?php if (session()->getFlashdata('mensaje_carrito')): ?>
        <div class="alert alert-success text-center">
            <?= session('mensaje_carrito') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger text-center">
            <?= session('error') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error_stock')): ?>
        <div class="alert alert-danger text-center">
            <?= session('getFlashdata')('error_stock') ?>
        </div>
    <?php endif; ?>

    <a href="<?= base_url('productos'); ?>" class="btn btn-outline-primary mb-4">← Seguir comprando</a>

    <?php if ($cart->contents() == NULL): ?>
        <div class="alert alert-info text-center">¡No tiene productos en el carrito!</div>
    <?php else: ?>
        <table class="table table-bordered table-hover align-middle text-center shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php $total = 0; $i = 1; ?>
                <?php foreach ($cart->contents() as $item): ?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td><?= esc($item['name']); ?></td>
                        <td>
                            <?php if (isset($item['options']['precio_original']) && $item['options']['precio_original'] > $item['price']): ?>
                                <span class="text-muted text-decoration-line-through">$<?= number_format($item['options']['precio_original'], 2); ?></span><br>
                                <span class="text-success fw-bold">$<?= number_format($item['price'], 2); ?></span>
                            <?php else: ?>
                                $<?= number_format($item['price'], 2); ?>
                            <?php endif; ?>
                        </td>

                        <td>
                            <form action="<?= base_url('actualizar_cantidad'); ?>" method="post" class="d-flex justify-content-center align-items-center gap-1">
                                <?= csrf_field() ?> <input type="hidden" name="rowid" value="<?= $item['rowid']; ?>">
                                <button type="submit" name="accion" value="restar" class="btn btn-sm btn-outline-secondary" <?= $item['qty'] <= 1 ? 'disabled' : ''; ?>>−</button>
                                <span class="px-2"><?= $item['qty']; ?></span>
                                <button type="submit" name="accion" value="sumar" class="btn btn-sm btn-outline-secondary">+</button>
                            </form>
                        </td>
                        <td>$<?= number_format($item['subtotal'], 2); ?></td>
                        <td>
                            <a href="<?= base_url('eliminar_item/'.$item['rowid']); ?>" class="btn btn-sm btn-danger">Eliminar</a>
                        </td>
                    </tr>
                    <?php $total += $item['subtotal']; ?>
                <?php endforeach; ?>
                <tr class="table-secondary fw-bold">
                    <td colspan="4" class="text-end">Total:</td>
                    <td colspan="2">$<?= number_format($total, 2); ?></td>
                </tr>
            </tbody>
        </table>

        <div class="d-flex justify-content-end gap-3">
            <a href="<?= base_url('vaciar_carrito'); ?>" class="btn btn-outline-danger">Vaciar carrito</a>
            <a href="<?= base_url('ordenar_compra'); ?>" class="btn btn-success">Ordenar compra</a>
        </div>
    <?php endif; ?>
</div>
