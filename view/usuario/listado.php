<?php
/** @var \App\Models\Usuario[] $usuarios */
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3">Usuarios</h1>
    <a href="index.php?c=usuario&a=create" class="btn btn-primary">
        Nuevo usuario
    </a>
</div>

<table class="table table-striped table-hover">
    <thead>
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Apellido</th>
        <th>Correo</th>
        <th>Tipo</th>
        <th>Estado</th>
        <th>Fecha registro</th>
        <th>Acciones</th>
    </tr>
    </thead>
    <tbody>
    <?php if (!empty($usuarios)): ?>
        <?php foreach ($usuarios as $u): ?>
            <?php
            $id          = method_exists($u, 'getIdUsuario')      ? $u->getIdUsuario()      : null;
            $nombre      = method_exists($u, 'getNombre')         ? $u->getNombre()         : '';
            $apellido    = method_exists($u, 'getApellido')       ? $u->getApellido()       : '';
            $email       = method_exists($u, 'getEmail')          ? $u->getEmail()          : '';
            $tipo        = method_exists($u, 'getTipoUsuario')    ? $u->getTipoUsuario()    : '';
            $estadoVal   = method_exists($u, 'getEstado')         ? (int)$u->getEstado()    : 1;
            $fechaReg    = method_exists($u, 'getFechaRegistro')  ? $u->getFechaRegistro()  : null;
            $estadoTexto = $estadoVal === 1 ? 'Activo' : 'Inactivo';
            ?>
            <tr>
                <td><?= htmlspecialchars((string)$id) ?></td>
                <td><?= htmlspecialchars($nombre) ?></td>
                <td><?= htmlspecialchars($apellido) ?></td>
                <td><?= htmlspecialchars($email) ?></td>
                <td><?= htmlspecialchars($tipo) ?></td>
                <td><?= htmlspecialchars($estadoTexto) ?></td>
                <td><?= htmlspecialchars($fechaReg ?? '-') ?></td>
                <td>
                    <a href="index.php?c=usuario&a=edit&id=<?= urlencode((string)$id) ?>"
                       class="btn btn-sm btn-warning">
                        Editar
                    </a>
                    <a href="index.php?c=usuario&a=delete&id=<?= urlencode((string)$id) ?>"
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('¿Seguro que deseas eliminar este usuario?');">
                        Eliminar
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="8" class="text-center">No hay usuarios registrados.</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>
