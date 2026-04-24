<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Gestión de Bebidas</h1>
        <a href="<?= site_url('agregar_bebida') ?>" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Agregar Nueva Bebida
        </a>
    </div>

    <?php if (session()->getFlashdata('mensaje')): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('mensaje') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?= form_open('gestionar_bebidas', ['method' => 'get', 'class' => 'row g-3 mb-4']) ?>
    <div class="row g-2">
        <div class="col-md-4">
            <select name="categoria" class="form-select">
                <option value="">Todas las categorías</option>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?= $cat['id_categoria'] ?>" <?= (isset($categoriaSeleccionada) && $categoriaSeleccionada == $cat['id_categoria']) ? 'selected' : '' ?>>
                        <?= esc($cat['nombre_categoria']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-5">
            <input type="text" name="busqueda" class="form-control" placeholder="Buscar por nombre o marca..." value="<?= esc($busqueda ?? '') ?>">
        </div>
        <div class="col-md-3 d-grid">
            <button type="submit" class="btn btn-dark">
                <i class="bi bi-search"></i> Filtrar
            </button>
        </div>
    </div>
    <?= form_close() ?>

    <?php if (empty($bebidas)): ?>
        <div class="alert alert-warning text-center shadow-sm">
            No se encontraron bebidas con los filtros aplicados.
        </div>
    <?php else: ?>
        <div class="table-responsive shadow-sm rounded">
            <table class="table table-bordered table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr class="text-center">
                        <th>Imagen</th>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Marca</th>
                        <th>Categoría</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bebidas as $p): ?>
                        <tr>
                            <td class="text-center">
                                <img src="<?= base_url('assets/upload/' . ($p['imagen_bebida'] ?: 'default.png')) ?>" 
                                     alt="Imagen" class="rounded border" width="50" height="50" style="object-fit: cover;">
                            </td>
                            <td>
                                <strong><?= esc($p['nombre_bebida']) ?></strong><br>
                                <small class="text-muted"><?= esc(substr($p['descripcion_bebida'], 0, 40)) ?>...</small>
                            </td>
                            <td class="text-center">$<?= number_format($p['precio_bebida'], 2, ',', '.') ?></td>
                            <td class="text-center">
                                <span class="badge <?= $p['stock_bebida'] <= 5 ? 'bg-danger' : 'bg-secondary' ?>">
                                    <?= esc($p['stock_bebida']) ?>
                                </span>
                            </td>
                            <td><?= esc($p['nombre_marca']) ?></td>
                            <td><?= esc($p['nombre_categoria']) ?></td>
                            <td class="text-center">
                                <?= $p['estado_bebida'] 
                                    ? '<span class="badge bg-success">Activo</span>' 
                                    : '<span class="badge bg-danger">Inactivo</span>' ?>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="<?= site_url('editar_bebida/' . $p['id_bebida']) ?>" 
                                       class="btn btn-sm btn-outline-warning" title="Editar">
                                       <i class="bi bi-pencil"></i>
                                    </a>
                                    
                                    <?php if ($p['estado_bebida']): ?>
                                        <a href="<?= site_url('deshabilitar_bebida/' . $p['id_bebida']) ?>" 
                                           class="btn btn-sm btn-outline-secondary" 
                                           onclick="return confirm('¿Deseás deshabilitar esta bebida?');">
                                           <i class="bi bi-eye-slash"></i>
                                        </a>
                                    <?php else: ?>
                                        <a href="<?= site_url('habilitar_bebida/' . $p['id_bebida']) ?>" 
                                           class="btn btn-sm btn-outline-success" 
                                           onclick="return confirm('¿Deseás habilitar esta bebida?');">
                                           <i class="bi bi-eye"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<script>
    // Auto-cerrar alertas después de 4 segundos
    setTimeout(() => {
        let alertNode = document.querySelector('.alert-dismissible');
        if (alertNode) {
            let alert = bootstrap.Alert.getOrCreateInstance(alertNode);
            alert.close();
        }
    }, 4000);
</script>

