<?php helper('form'); ?>

<div class="container d-flex align-items-center justify-content-center min-vh-100 py-5">
    <div class="card p-4 shadow rounded" style="max-width: 500px; width: 100%;">
        <h3 class="text-center mb-4">Crear una cuenta</h3>
        
        <?= form_open('form_registro') ?>
            <?= csrf_field() ?>
            
            <input type="hidden" name="id_perfil" value="2">

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control <?= (isset($validation) && is_array($validation) && isset($validation['nombre_usuario'])) ? 'is-invalid' : '' ?>" 
                       id="nombre" name="nombre_usuario" value="<?= set_value('nombre_usuario') ?>">
                <?php if (isset($validation) && is_array($validation) && isset($validation['nombre_usuario'])): ?>
                    <div class="invalid-feedback"><?= $validation['nombre_usuario'] ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="apellido" class="form-label">Apellido</label>
                <input type="text" class="form-control <?= (isset($validation) && is_array($validation) && isset($validation['apellido_usuario'])) ? 'is-invalid' : '' ?>" 
                       id="apellido" name="apellido_usuario" value="<?= set_value('apellido_usuario') ?>">
                <?php if (isset($validation) && is_array($validation) && isset($validation['apellido_usuario'])): ?>
                    <div class="invalid-feedback"><?= $validation['apellido_usuario'] ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="usuario" class="form-label">Nombre de Usuario</label>
                <input type="text" class="form-control <?= (isset($validation) && is_array($validation) && isset($validation['usuario'])) ? 'is-invalid' : '' ?>" 
                       id="usuario" name="usuario" value="<?= set_value('usuario') ?>">
                <?php if (isset($validation) && is_array($validation) && isset($validation['usuario'])): ?>
                    <div class="invalid-feedback"><?= $validation['usuario'] ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Correo electrónico</label>
                <input type="email" class="form-control <?= (isset($validation) && is_array($validation) && isset($validation['email_usuario'])) ? 'is-invalid' : '' ?>" 
                       id="email" name="email_usuario" value="<?= set_value('email_usuario') ?>" placeholder="ejemplo@correo.com">
                <?php if (isset($validation) && is_array($validation) && isset($validation['email_usuario'])): ?>
                    <div class="invalid-feedback"><?= $validation['email_usuario'] ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="pass" class="form-label">Contraseña</label>
                <input type="password" class="form-control <?= (isset($validation) && is_array($validation) && isset($validation['pass_usuario'])) ? 'is-invalid' : '' ?>" 
                       id="pass" name="pass_usuario">
                <?php if (isset($validation) && is_array($validation) && isset($validation['pass_usuario'])): ?>
                    <div class="invalid-feedback"><?= $validation['pass_usuario'] ?></div>
                <?php endif; ?>
            </div>

            <div class="d-grid mt-4">
                <button type="submit" class="btn btn-dark btn-lg">Registrarse</button>
            </div>
            
            <div class="text-center mt-3">
                <small>¿Ya tenés cuenta? <a href="<?= base_url('login') ?>">Iniciá sesión</a></small>
            </div>
        <?= form_close() ?>
    </div>
</div>