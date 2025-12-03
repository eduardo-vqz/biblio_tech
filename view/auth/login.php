<?php
$error = $error ?? null;
?>
<div class="row justify-content-center">
    <div class="col-md-4">
        <h1 class="h3 mb-3 text-center">Iniciar sesión</h1>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form action="index.php?c=auth&a=authenticate" method="post">
            <div class="mb-3">
                <label for="email" class="form-label">Correo electrónico</label>
                <input type="email"
                       id="email"
                       name="email"
                       class="form-control"
                       required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password"
                       id="password"
                       name="password"
                       class="form-control"
                       required>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary">
                    Ingresar
                </button>
            </div>
        </form>
    </div>
</div>
