<div class="container mt-4">
    <h1 class="text-center mb-4">Gestión de Usuarios</h1>

    <?= form_open('usuarios', ['method' => 'get', 'class' => 'row g-3 mb-4 shadow-sm p-3 bg-light rounded']) ?>
        <div class="col-md-4">
            <label for="perfil" class="form-label fw-bold">Filtrar por perfil</label>
            <select name="perfil" id="perfil" class="form-select">
                <option value="">Todos los roles</option>
                <option value="1" <?= set_select('perfil', '1', (isset($_GET['perfil']) && $_GET['perfil'] == '1')) ?>>Administrador</option>
                <option value="2" <?= set_select('perfil', '2', (isset($_GET['perfil']) && $_GET['perfil'] == '2')) ?>>Cliente</option>
            </select>
        </div>

        <div class="col-md-4">
            <label for="email" class="form-label fw-bold">Buscar por email</label>
            <input type="text" name="email" id="email" 
                   value="<?= esc($_GET['email'] ?? '') ?>" 
                   class="form-control" placeholder="nombre@ejemplo.com">
        </div>

        <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-primary me-2">
                <i class="bi bi-search"></i> Filtrar
            </button>
            <a href="<?= site_url('usuarios') ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-clockwise"></i> Reiniciar
            </a>
        </div>
    <?= form_close() ?>

    <?php if (session()->getFlashdata('mensaje')): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('mensaje') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="table-responsive shadow-sm rounded">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th class="text-center">ID</th>
                    <th>Nombre Completo</th>
                    <th>Usuario</th>
                    <th>Email</th>
                    <th class="text-center">Perfil</th>
                    <th class="text-center">Estado</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($usuarios)): ?>
                    <?php foreach ($usuarios as $u): ?>
                        <tr>
                            <td class="text-center fw-bold">#<?= $u['id_usuario'] ?></td>
                            <td><?= esc($u['nombre_usuario'] . ' ' . $u['apellido_usuario']) ?></td>
                            <td><code class="text-primary"><?= esc($u['usuario']) ?></code></td>
                            <td><?= esc($u['email_usuario']) ?></td>
                            <td class="text-center">
                                <?php if ($u['id_perfil'] == 1): ?>
                                    <span class="badge rounded-pill bg-primary">Admin</span>
                                <?php else: ?>
                                    <span class="badge rounded-pill bg-info text-dark">Cliente</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if ($u['baja'] == '1'): ?>
                                    <span class="badge bg-danger">
                                        <i class="bi bi-x-circle"></i> Suspendido
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle"></i> Activo
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if (session('id_usuario') != $u['id_usuario']): ?>
                                    <?php if ($u['baja'] == '1'): ?>
                                        <a href="<?= site_url('habilitar_usuario/' . $u['id_usuario']) ?>" 
                                           class="btn btn-sm btn-success w-75"
                                           onclick="return confirm('¿Habilitar al usuario <?= esc($u['usuario']) ?>?')">
                                           <i class="bi bi-person-check"></i> Habilitar
                                        </a>
                                    <?php else: ?>
                                        <a href="<?= site_url('suspender_usuario/' . $u['id_usuario']) ?>" 
                                           class="btn btn-sm btn-outline-danger w-75"
                                           onclick="return confirm('¿Estás seguro de suspender a <?= esc($u['usuario']) ?>?')">
                                           <i class="bi bi-person-dash"></i> Suspender
                                        </a>
                                    <?php endif; ?> 
                                <?php else: ?>
                                    <span class="badge bg-secondary opacity-50">Mi Usuario</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted fst-italic">
                            No se encontraron usuarios que coincidan con los filtros.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

