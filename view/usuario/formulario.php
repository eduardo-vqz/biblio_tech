<?php
/** @var \App\Models\Usuario $usuario */

$esEdicion = method_exists($usuario, 'getIdUsuario') && $usuario->getIdUsuario() !== null;
$action    = $esEdicion ? 'update' : 'store';

$nombre    = method_exists($usuario, 'getNombre')        ? $usuario->getNombre()        : '';
$apellido  = method_exists($usuario, 'getApellido')      ? $usuario->getApellido()      : '';
$email     = method_exists($usuario, 'getEmail')         ? $usuario->getEmail()         : '';
$tipo      = method_exists($usuario, 'getTipoUsuario')   ? $usuario->getTipoUsuario()   : 'ESTUDIANTE';
$estadoVal = method_exists($usuario, 'getEstado')        ? (int)$usuario->getEstado()   : 1;

// Si algún momento agregas teléfono al modelo:
$telefono  = method_exists($usuario, 'getTelefono')      ? $usuario->getTelefono()      : '';

$tipos = ['ADMIN', 'BIBLIOTECARIO', 'ESTUDIANTE'];
?>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <h1 class="h3 mb-3">
            <?= $esEdicion ? 'Editar usuario' : 'Nuevo usuario' ?>
        </h1>

        <form action="index.php?c=usuario&a=<?= $action ?>" method="post">
            <?php if ($esEdicion): ?>
                <input type="hidden" name="id_usuario"
                       value="<?= htmlspecialchars((string)$usuario->getIdUsuario()) ?>">
            <?php endif; ?>

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text"
                       id="nombre"
                       name="nombre"
                       class="form-control"
                       required
                       value="<?= htmlspecialchars($nombre) ?>">
            </div>

            <div class="mb-3">
                <label for="apellido" class="form-label">Apellido</label>
                <input type="text"
                       id="apellido"
                       name="apellido"
                       class="form-control"
                       required
                       value="<?= htmlspecialchars($apellido) ?>">
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Correo electrónico</label>
                <input type="email"
                       id="email"
                       name="email"
                       class="form-control"
                       required
                       value="<?= htmlspecialchars($email) ?>">
            </div>

            <?php if ($telefono !== ''): ?>
                <div class="mb-3">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="text"
                           id="telefono"
                           name="telefono"
                           class="form-control"
                           value="<?= htmlspecialchars($telefono) ?>">
                </div>
            <?php endif; ?>

            <div class="mb-3">
                <label for="tipo_usuario" class="form-label">Tipo de usuario</label>
                <select id="tipo_usuario" name="tipo_usuario" class="form-select">
                    <?php foreach ($tipos as $t): ?>
                        <option value="<?= $t ?>"
                            <?= $t === $tipo ? 'selected' : '' ?>>
                            <?= $t ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label d-block">Estado</label>
                <!-- Enviamos 0 por defecto y 1 si está marcado -->
                <input type="hidden" name="estado" value="0">
                <div class="form-check form-switch">
                    <input class="form-check-input"
                           type="checkbox"
                           id="estado"
                           name="estado"
                           value="1"
                        <?= $estadoVal === 1 ? 'checked' : '' ?>>
                    <label class="form-check-label" for="estado">
                        Usuario activo
                    </label>
                </div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">
                    <?= $esEdicion ? 'Nueva contraseña (opcional)' : 'Contraseña' ?>
                </label>
                <input type="password"
                       id="password"
                       name="password"
                       class="form-control"
                    <?= $esEdicion ? '' : 'required' ?>>
                <?php if ($esEdicion): ?>
                    <div class="form-text">
                        Si dejas este campo vacío, se conservará la contraseña actual.
                    </div>
                <?php endif; ?>
            </div>

            <div class="d-flex justify-content-between">
                <a href="index.php?c=usuario&a=index" class="btn btn-secondary">
                    Cancelar
                </a>
                <button type="submit" class="btn btn-success">
                    <?= $esEdicion ? 'Actualizar' : 'Guardar' ?>
                </button>
            </div>
        </form>
    </div>
</div>
