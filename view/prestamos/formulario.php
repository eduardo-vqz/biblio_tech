<?php
$usuarioSesion = $_SESSION['usuario'] ?? null;
$tipoSesion    = $usuarioSesion['tipo'] ?? '';
?>

<div class="mb-3">
    <label for="id_usuario" class="form-label">Usuario</label>

    <?php if ($tipoSesion === 'ESTUDIANTE'): ?>
        <input type="text" class="form-control" readonly
               value="<?= htmlspecialchars($usuarioSesion['nombre'] . ' (' . $usuarioSesion['email'] . ')') ?>">
        <!-- No enviamos campo id_usuario, lo toma del backend -->
    <?php else: ?>
        <select id="id_usuario" name="id_usuario" class="form-select" required>
            <option value="">-- Seleccione usuario --</option>
            <?php foreach ($usuarios as $u): ?>
                <option value="<?= $u->getIdUsuario() ?>"
                    <?= $prestamo->getIdUsuario() === $u->getIdUsuario() ? 'selected' : '' ?>>
                    <?= htmlspecialchars($u->getNombreCompleto() . ' (' . $u->getEmail() . ')') ?>
                </option>
            <?php endforeach; ?>
        </select>
    <?php endif; ?>
</div>
