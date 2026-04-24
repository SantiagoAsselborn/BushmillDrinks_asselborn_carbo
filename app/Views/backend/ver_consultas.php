<div class="container mt-4 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Consultas de Clientes</h1>
        <span class="badge bg-secondary fs-6"><?= is_array($mensajes) ? count($mensajes) : 0 ?> total</span>
    </div>

    <?php if (session()->has('mensaje')): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle me-2"></i> <?= session('mensaje') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (empty($mensajes)): ?>
        <div class="alert alert-info text-center py-5">
            <i class="bi bi-envelope-open fs-1 d-block mb-2"></i> No hay consultas pendientes.
        </div>
    <?php else: ?>
        <div class="table-responsive shadow-sm rounded">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Fecha</th> <th>Cliente</th>
                        <th>Contacto</th>
                        <th>Consulta</th>
                        <th class="text-center">Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($mensajes as $m): ?>
                        <tr class="<?= !$m['estado_mensaje'] ? 'table-warning fw-bold' : '' ?>">
                            <td>#<?= esc($m['id_mensaje']) ?></td>
                            <td><small class="text-muted"><?= isset($m['created_at']) ? date('d/m/Y', strtotime($m['created_at'])) : '---' ?></small></td>
                            <td>
                                <div><?= esc($m['nombre_mensaje']) ?></div>
                            </td>
                            <td>
                                <div class="small"><i class="bi bi-envelope"></i> <?= esc($m['mail_mensaje']) ?></div>
                                <div class="small"><i class="bi bi-telephone"></i> <?= esc($m['telefono_mensaje']) ?></div>
                            </td>
                            <td style="max-width: 300px;"><?= esc($m['consulta_mensaje']) ?></td>
                            <td class="text-center">
                                <?php if ($m['estado_mensaje']): ?>
                                    <span class="badge bg-success rounded-pill">Leído</span>
                                <?php else: ?>
                                    <span class="badge bg-danger rounded-pill">Pendiente</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <a href="<?= site_url('marcar_leido/'.$m['id_mensaje']) ?>" 
                                   class="btn btn-sm <?= $m['estado_mensaje'] ? 'btn-outline-secondary' : 'btn-success' ?>"
                                   title="<?= $m['estado_mensaje'] ? 'Marcar como no leído' : 'Marcar como leído' ?>">
                                   <i class="bi <?= $m['estado_mensaje'] ? 'bi-arrow-counterclockwise' : 'bi-check-lg' ?>"></i>
                                   <?= $m['estado_mensaje'] ? 'Reabrir' : 'Atendido' ?>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
