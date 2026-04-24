<div class="container d-flex align-items-center justify-content-center min-vh-100 py-5">
    <div class="card p-4 shadow rounded" style="max-width: 500px; width: 100%;">
        <h3 class="text-center mb-4">Editar perfil</h3>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success text-center"><?= esc(session()->getFlashdata('success')) ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error') || (isset($error) && !empty($error))): ?>
            <div class="alert alert-danger text-center">
                <?= esc(session()->getFlashdata('error') ?? $error) ?>
            </div>
        <?php endif; ?>

        <?= form_open('actualizar_perfil') ?>
            <?= csrf_field() ?>
            <input type="hidden" name="id_usuario" value="<?= esc($usuario['id_usuario']) ?>">

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control <?= isset($validation['nombre_usuario']) ? 'is-invalid' : '' ?>" 
                       name="nombre_usuario" value="<?= old('nombre_usuario', $usuario['nombre_usuario']) ?>">
                <?php if (isset($validation['nombre_usuario'])): ?>
                    <div class="invalid-feedback"><?= esc($validation['nombre_usuario']) ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="apellido" class="form-label">Apellido</label>
                <input type="text" class="form-control <?= isset($validation['apellido_usuario']) ? 'is-invalid' : '' ?>" 
                       name="apellido_usuario" value="<?= old('apellido_usuario', $usuario['apellido_usuario']) ?>">
                <?php if (isset($validation['apellido_usuario'])): ?>
                    <div class="invalid-feedback"><?= esc($validation['apellido_usuario']) ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="usuario" class="form-label">Nombre de Usuario</label>
                <input type="text" class="form-control <?= isset($validation['usuario']) ? 'is-invalid' : '' ?>" 
                       name="usuario" value="<?= old('usuario', $usuario['usuario']) ?>">
                <?php if (isset($validation['usuario'])): ?>
                    <div class="invalid-feedback"><?= esc($validation['usuario']) ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Correo electrónico</label>
                <input type="email" class="form-control <?= isset($validation['email_usuario']) ? 'is-invalid' : '' ?>" 
                       name="email_usuario" value="<?= old('email_usuario', $usuario['email_usuario']) ?>">
                <?php if (isset($validation['email_usuario'])): ?>
                    <div class="invalid-feedback"><?= esc($validation['email_usuario']) ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="pass" class="form-label">Nueva contraseña (opcional)</label>
                <input type="password" class="form-control <?= isset($validation['pass_usuario']) ? 'is-invalid' : '' ?>" name="pass_usuario">
                <small class="text-muted">Dejar en blanco para mantener la contraseña actual</small>
                <?php if (isset($validation['pass_usuario'])): ?>
                    <div class="invalid-feedback d-block"><?= esc($validation['pass_usuario']) ?></div>
                <?php endif; ?>
            </div>

            <div class="d-grid mt-4">
                <button type="submit" class="btn btn-primary btn-lg">Actualizar Perfil</button>
            </div>
        <?= form_close() ?>
    </div>
</div>

