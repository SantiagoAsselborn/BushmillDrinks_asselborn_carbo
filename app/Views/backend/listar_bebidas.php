<div class="container mt-4">
    <h1 class="text-center mb-4">Lista de Bebidas</h1>

    <?= form_open('listar_bebidas', ['method' => 'get', 'class' => 'mb-4']) ?>
    <div class="row g-2">
        <div class="col-md-4">
            <select name="id_categoria" class="form-select">
                <option value="">Todas las categorías</option>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?= $cat['id_categoria'] ?>" <?= set_select('id_categoria', $cat['id_categoria']) ?>>
                        <?= esc($cat['nombre_categoria']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-5">
            <input type="text" name="busqueda" class="form-control" 
                   placeholder="Buscar por nombre o marca..." 
                   value="<?= esc($busqueda ?? '') ?>">
        </div>

        <div class="col-md-3 d-grid">
            <button type="submit" class="btn btn-dark">
                <i class="bi bi-filter"></i> Filtrar
            </button>
        </div>
    </div>
    <?= form_close() ?>

    <?php if (empty($bebidas)): ?>
        <div class="alert alert-warning text-center shadow-sm mt-3">
            No se encontraron productos con los criterios ingresados.
        </div>
    <?php else: ?>
        <div class="table-responsive shadow-sm rounded">
            <table class="table table-striped table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Nombre</th>
                        <th>Marca</th>
                        <th>Precio</th>
                        <th class="text-center">Stock</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bebidas as $p): ?>
                        <tr>
                            <td><strong><?= esc($p['nombre_bebida']) ?></strong></td>
                            <td><?= esc($p['nombre_marca']) ?></td>
                            <td>$<?= number_format($p['precio_bebida'], 2, ',', '.') ?></td>
                            <td class="text-center">
                                <span class="badge <?= $p['stock_bebida'] < 5 ? 'bg-danger' : 'bg-success' ?>">
                                    <?= esc($p['stock_bebida']) ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
