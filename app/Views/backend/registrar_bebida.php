<div class="container mt-4 mb-5">
    <div class="card shadow-sm w-75 mx-auto">
        <div class="card-header bg-dark text-white">
            <h2 class="text-center mb-0"><?= isset($bebida) ? 'Editar Bebida' : 'Registro de Bebida' ?></h2>
        </div>
        <div class="card-body">
            
            <?php if (!empty($validation)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($validation as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach ?>
                    </ul>
                </div>
            <?php endif ?>

            <?= form_open_multipart(isset($bebida) ? 'actualizar_bebida/'.$bebida['id_bebida'] : 'insertar_bebida') ?>
            
            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label">Nombre de la bebida</label>
                    <input type="text" name="nombre_bebida" class="form-control" value="<?= set_value('nombre_bebida', $bebida['nombre_bebida'] ?? '') ?>" required>
                </div>

                <div class="col-md-12">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion_bebida" class="form-control" rows="3"><?= set_value('descripcion_bebida', $bebida['descripcion_bebida'] ?? '') ?></textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Precio ($)</label>
                    <input type="number" step="0.01" name="precio_bebida" class="form-control" value="<?= set_value('precio_bebida', $bebida['precio_bebida'] ?? '') ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Stock</label>
                    <input type="number" name="stock_bebida" class="form-control" value="<?= set_value('stock_bebida', $bebida['stock_bebida'] ?? '') ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Volumen (ml)</label>
                    <input type="number" name="volumen_bebida" class="form-control" value="<?= set_value('volumen_bebida', $bebida['volumen_bebida'] ?? '') ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Grado alcohólico (%)</label>
                    <input type="number" step="0.1" name="grado_bebida" class="form-control" value="<?= set_value('grado_bebida', $bebida['grado_bebida'] ?? '') ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Marca</label>
                    <select name="id_marca" class="form-select">
                        <option value="">Seleccione marca</option>
                        <?php foreach($marca as $row): ?>
                            <option value="<?= $row['id_marca'] ?>" <?= set_select('id_marca', $row['id_marca'], (isset($bebida) && $bebida['id_marca'] == $row['id_marca'])) ?>><?= esc($row['nombre_marca']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Categoría</label>
                    <select name="id_categoria" class="form-select">
                        <option value="">Seleccione categoría</option>
                        <?php foreach ($categoria as $cat): ?>
                            <option value="<?= $cat['id_categoria'] ?>" <?= set_select('id_categoria', $cat['id_categoria'], (isset($bebida) && $bebida['id_categoria'] == $cat['id_categoria'])) ?>><?= esc($cat['nombre_categoria']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-12">
                    <label class="form-label">Imagen del producto</label>
                    <input type="file" name="imagen_bebida" class="form-control">
                    <?php if (isset($bebida['imagen_bebida'])): ?>
                        <div class="mt-2">
                            <small class="text-muted">Imagen actual:</small><br>
                            <img src="<?= base_url('assets/upload/' . $bebida['imagen_bebida']) ?>" width="100" class="img-thumbnail">
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="d-grid mt-4">
                <button type="submit" class="btn btn-primary btn-lg"><?= isset($bebida) ? 'Actualizar Bebida' : 'Registrar Bebida' ?></button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>