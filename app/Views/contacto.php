<?php if(session()->getFlashdata('mensaje')): ?>
    <div class="alert alert-success" role="alert">
        <?= session()->getFlashdata('mensaje') ?>
    </div>
<?php endif; ?>

<section style="margin-bottom: 50px">
    <div class="container-md">
        <img src="assets/img/fondoContacto.png" alt="fondo contactanos" style="width: 100%; height: auto;">
    </div>   

    <div class="container">
        <form method="post" action="<?= base_url('form_consulta') ?>">
            <div class="row">
                <div class="col" style="margin-top: 20px;"> 
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre_mensaje" placeholder="Tu nombre" value="<?=set_value('nombre_mensaje')?>">
                        <?php if (isset($validation) && $validation->hasError('nombre_mensaje')): ?>
                            <small class="text-danger"><?= $validation->getError('nombre_mensaje') ?></small>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Dirección de Email</label>
                        <input type="email" class="form-control" id="email" name="mail_mensaje" placeholder="nombre@ejemplo.com" value="<?=set_value('mail_mensaje')?>">
                        <?php if (isset($validation) && $validation->hasError('mail_mensaje')): ?>
                            <small class="text-danger"><?= $validation->getError('mail_mensaje') ?></small>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label for="telefono" class="form-label">Número de teléfono</label>
                        <input type="tel" class="form-control" id="telefono" name="telefono_mensaje" placeholder="Ejemplo: 3794 123456" value="<?=set_value('telefono_mensaje')?>">
                        <?php if (isset($validation) && $validation->hasError('telefono_mensaje')): ?>
                            <small class="text-danger"><?= $validation->getError('telefono_mensaje') ?></small>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label for="consulta" class="form-label">Comentarios</label>
                        <textarea class="form-control" id="consulta" name="consulta_mensaje" rows="3"><?= set_value('consulta_mensaje') ?></textarea>
                        <?php if (isset($validation) && $validation->hasError('consulta_mensaje')): ?>
                            <small class="text-danger"><?= $validation->getError('consulta_mensaje') ?></small>
                        <?php endif; ?>
                    </div>
                    <button type="submit" class="btn btn-secondary">Enviar</button>
                </div>

                <div class="col" style="margin-top: 20px;">
                    <p><strong>Email</strong></p>
                    <p>soporte@bushmill.com</p>
                    <p><strong>Teléfono</strong></p>
                    <p>+54 11 1234-5678</p>
                    <p><strong>Horario de atención</strong></p>
                    <p>Lunes a Viernes, de 17 a 00 hs</p>
                    <p><strong>Dirección</strong></p>
                    <p>9 de Julio 745, Corrientes Capital</p>
                    <p>Lavalle 1534, Corrientes Capital</p>
                </div>
            </div>
        </form>
    </div>
</section>

