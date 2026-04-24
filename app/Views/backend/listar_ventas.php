<div class="container mt-4">
    <h1 class="text-center mb-4">Historial de Ventas</h1>

    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0"><i class="bi bi-funnel me-2"></i>Filtrar por Rango de Fechas</h5>
        </div>
        <div class="card-body bg-light">
            <?= form_open('listar_ventas', ['method' => 'get', 'class' => 'row g-3']) ?>
                <div class="col-md-5">
                    <label for="fecha_inicio" class="form-label fw-bold">Fecha desde</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-calendar-event"></i></span>
                        <input type="text" class="form-control datepicker" id="fecha_inicio" name="fecha_inicio" 
                               value="<?= !empty($fechaInicio) ? esc($fechaInicio) : '' ?>" placeholder="dd/mm/aaaa" autocomplete="off">
                    </div>
                </div>
                <div class="col-md-5">
                    <label for="fecha_fin" class="form-label fw-bold">Fecha hasta</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-calendar-check"></i></span>
                        <input type="text" class="form-control datepicker" id="fecha_fin" name="fecha_fin" 
                               value="<?= !empty($fechaFin) ? esc($fechaFin) : '' ?>" placeholder="dd/mm/aaaa" autocomplete="off">
                    </div>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Filtrar
                    </button>
                </div>
            <?= form_close() ?>
            
            <?php if (!empty($fechaInicio) || !empty($fechaFin)): ?>
                <div class="mt-3">
                    <a href="<?= site_url('listar_ventas') ?>" class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-x-circle"></i> Limpiar Filtros
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if (!empty($ventasAgrupadas)): ?>
        <div class="accordion" id="ventasAccordion">
            <?php foreach ($ventasAgrupadas as $venta): ?>
                <div class="accordion-item mb-3 border rounded-3 shadow-sm">
                    <h2 class="accordion-header" id="heading<?= $venta['id_venta'] ?>">
                        <button class="accordion-button collapsed py-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $venta['id_venta'] ?>">
                            <div class="d-flex justify-content-between w-100 pe-3 align-items-center">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-dark me-3">#<?= $venta['id_venta'] ?></span>
                                    <div>
                                        <h6 class="mb-0 fw-bold"><?= esc($venta['cliente']) ?></h6>
                                        <small class="text-muted"><?= date('d/m/Y H:i', strtotime($venta['fecha'])) ?> hs.</small>
                                    </div>
                                </div>
                                <span class="badge bg-success fs-6">$<?= number_format($venta['total'], 2, ',', '.') ?></span>
                            </div>
                        </button>
                    </h2>
                    <div id="collapse<?= $venta['id_venta'] ?>" class="accordion-collapse collapse" data-bs-parent="#ventasAccordion">
                        <div class="accordion-body pt-4">
                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <div class="card h-100 border-start border-primary border-4">
                                        <div class="card-body">
                                            <h6 class="card-title text-primary fw-bold mb-3">Información del Comprador</h6>
                                            <p class="mb-1"><i class="bi bi-person me-2"></i><strong>Cliente:</strong> <?= esc($venta['cliente']) ?></p>
                                            <p class="mb-0"><i class="bi bi-envelope me-2"></i><strong>Email:</strong> <?= esc($venta['email']) ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card h-100 border-start border-info border-4">
                                        <div class="card-body">
                                            <h6 class="card-title text-info fw-bold mb-3">Logística de Envío</h6>
                                            <p class="mb-1"><i class="bi bi-geo-alt me-2"></i><strong>Dirección:</strong> <?= esc($venta['direccion']) ?></p>
                                            <p class="mb-0"><i class="bi bi-mailbox me-2"></i><strong>C.P.:</strong> <?= esc($venta['codigo_postal']) ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <h6 class="fw-bold mb-3"><i class="bi bi-box-seam me-2"></i>Ítems de la Venta</h6>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Producto</th>
                                            <th class="text-center">Cant.</th>
                                            <th class="text-end">P. Unitario</th>
                                            <th class="text-end">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($venta['productos'] as $prod): ?>
                                            <tr>
                                                <td><?= esc($prod['nombre']) ?></td>
                                                <td class="text-center"><?= $prod['cantidad'] ?></td>
                                                <td class="text-end">$<?= number_format($prod['precio'], 2, ',', '.') ?></td>
                                                <td class="text-end">$<?= number_format($prod['subtotal'], 2, ',', '.') ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <tr class="table-dark fw-bold">
                                            <td colspan="3" class="text-end">TOTAL RECAUDADO:</td>
                                            <td class="text-end">$<?= number_format($venta['total'], 2, ',', '.') ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-warning text-center py-5 shadow-sm">
            <i class="bi bi-exclamation-triangle fs-1 d-block mb-3"></i>
            <h4>No se encontraron registros</h4>
            <p class="mb-0">Pruebe ajustando el rango de fechas o verifique si hay ventas cargadas.</p>
        </div>
    <?php endif; ?>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/css/bootstrap-datepicker.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/locales/bootstrap-datepicker.es.min.js"></script>

<script>
$(document).ready(function(){
    $('.datepicker').datepicker({
        format: 'dd/mm/yyyy',
        language: 'es',
        autoclose: true,
        todayHighlight: true,
        orientation: "bottom auto"
    });
});
</script>



