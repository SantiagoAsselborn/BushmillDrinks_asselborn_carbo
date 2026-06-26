<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold text-dark">📦 Datos de Envío</h1>
        <a href="<?= base_url('ver_carrito') ?>" class="btn btn-outline-success">← Volver al carrito</a>
    </div>

    <div class="card shadow-sm rounded-4 border-0">
        <div class="card-body p-4">
            <?= form_open('confirmar_compra') ?>
            <?= csrf_field() ?>
            <input
                type="hidden"
                id="total_compra"
                value="<?= $total ?>">
            <div class="row g-4">
                <div class="col-md-6">
                    <label for="telefono" class="form-label">📱 Teléfono</label>
                    <input type="tel" class="form-control" id="telefono" name="envio_telefono" required value="<?= set_value('envio_telefono') ?>">
                </div>

                <div class="col-md-6">
                    <label for="direccion" class="form-label">🏠 Calle</label>
                    <input type="text" class="form-control" id="direccion" name="calle" placeholder="Ej: Av. Siempreviva" required value="<?= set_value('calle') ?>">
                </div>

                <div class="col-md-3">
                    <label for="altura" class="form-label">#️⃣ Altura</label>
                    <input type="number" class="form-control" id="altura" name="altura" placeholder="Ej: 742" required value="<?= set_value('altura') ?>">
                </div>

                <div class="col-md-4">
                    <label for="id_provincia" class="form-label">📍 Provincia</label>
                    <select id="id_provincia" class="form-select" onchange="filtrarCiudades()" required>
                        <option value="">-- Seleccione una provincia --</option>
                        <?php foreach ($provincias as $provincia): ?>
                            <option value="<?= $provincia['id_provincia'] ?>">
                                <?= esc($provincia['nombre_provincia']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-9">
                    <label for="id_ciudad" class="form-label">🏙️ Ciudad</label>
                    <select name="id_ciudad" id="id_ciudad" class="form-select" required>
                        <option value="">-- Seleccione una ciudad --</option>
                        <?php foreach ($ciudades as $ciudad): ?>
                            <option value="<?= $ciudad['id_ciudad'] ?>"
                                data-provincia="<?= $ciudad['id_provincia'] ?>"
                                style="display:none;"
                                <?= set_select('id_ciudad', $ciudad['id_ciudad']) ?>>
                                <?= esc($ciudad['nombre_ciudad']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="codigo" class="form-label">📮 Código Postal</label>
                    <input type="number" class="form-control" id="codigo" name="codigo_postal" placeholder="Ej: 3500" required value="<?= set_value('codigo_postal') ?>">
                </div>

                <div class="col-md-6">
                    <label for="medio_pago" class="form-label">💳 Medio de pago</label>
                    <select name="medio_pago" id="medio_pago" class="form-select" required>
                        <option value="">-- Selecciona un medio de pago --</option>
                        <option value="efectivo">Efectivo</option>
                        <option value="transferencia">Transferencia</option>
                        <option value="tarjeta">Tarjeta</option>
                    </select>
                </div>

                <div class="col-12 mt-3">
                    <div id="detallePago"></div>
                </div>

                <div class="col-12 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary px-4 py-2">Confirmar Envío</button>
                </div>
            </div>
            <?= form_close() ?>
        </div>
    </div>
</div>

<script>
    function filtrarCiudades() {
        var provinciaId = document.getElementById('id_provincia').value;
        var ciudadSelect = document.getElementById('id_ciudad');
        var opciones = ciudadSelect.getElementsByTagName('option');

        // Resetear al mensaje inicial
        ciudadSelect.value = "";

        for (var i = 0; i < opciones.length; i++) {
            var opt = opciones[i];
            if (opt.value === "") continue; // Saltar la opción por defecto

            if (opt.getAttribute('data-provincia') === provinciaId) {
                opt.style.display = 'block';
            } else {
                opt.style.display = 'none';
            }
        }
    }
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        const medioPago = document.getElementById("medio_pago");

        medioPago.addEventListener("change", function() {

            let medio = this.value;

            if (medio == "") {
                document.getElementById("detallePago").innerHTML = "";
                return;
            }

            let total = document.getElementById("total_compra").value;

            fetch("<?= base_url('carrito/obtenerFormularioPago') ?>", {

                    method: "POST",

                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },

                    body: "medio=" + encodeURIComponent(medio) +
                        "&total=" + encodeURIComponent(total)

                })

                .then(response => response.json())

                .then(data => {

                    document.getElementById("detallePago").innerHTML = data.html;

                });

        });

    });
</script>