<?php
/** @var \App\Models\Prestamo[] $prestamos */
/** @var \App\Models\Usuario[] $usuariosMap */
/** @var \App\Models\Libro[] $librosMap */
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3">Préstamos</h1>
    <a href="index.php?c=prestamo&a=create" class="btn btn-primary">Nuevo préstamo</a>
</div>

<table class="table table-striped table-hover">
    <thead>
    <tr>
        <th>ID</th>
        <th>Usuario</th>
        <th>Libro</th>
        <th>Fecha préstamo</th>
        <th>Fecha devolución</th>
        <th>Fecha devuelto</th>
        <th>Estado</th>
        <th>Acciones</th>
    </tr>
    </thead>
    <tbody>
    <?php if (!empty($prestamos)): ?>
        <?php foreach ($prestamos as $p): ?>
            <?php
            $usuario = $usuariosMap[$p->getIdUsuario()] ?? null;
            $libro   = $librosMap[$p->getIdLibro()] ?? null;
            ?>
            <tr>
                <td><?= htmlspecialchars($p->getIdPrestamo()) ?></td>
                <td>
                    <?= $usuario ? htmlspecialchars($usuario->getNombreCompleto()) : 'N/D' ?>
                </td>
                <td>
                    <?= $libro ? htmlspecialchars($libro->getTitulo()) : 'N/D' ?>
                </td>
                <td><?= htmlspecialchars($p->getFechaPrestamo()) ?></td>
                <td><?= htmlspecialchars($p->getFechaDevolucion()) ?></td>
                <td><?= htmlspecialchars($p->getFechaDevuelto() ?? '-') ?></td>
                <td><?= htmlspecialchars($p->getEstado()) ?></td>
                <td>
                    <a href="index.php?c=prestamo&a=edit&id=<?= $p->getIdPrestamo() ?>"
                       class="btn btn-sm btn-warning">
                        Editar
                    </a>

                    <?php if (!$p->estaDevuelto()): ?>
                        <a href="index.php?c=prestamo&a=marcarDevuelto&id=<?= $p->getIdPrestamo() ?>"
                           class="btn btn-sm btn-success"
                           onclick="return confirm('¿Marcar como devuelto?');">
                            Devolver
                        </a>
                    <?php endif; ?>

                    <a href="index.php?c=prestamo&a=delete&id=<?= $p->getIdPrestamo() ?>"
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('¿Seguro que deseas eliminar este préstamo?');">
                        Eliminar
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="8" class="text-center">No hay préstamos registrados.</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>
