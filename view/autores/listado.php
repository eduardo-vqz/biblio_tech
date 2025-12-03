<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3">Autores</h1>
    <a href="index.php?c=autor&a=create" class="btn btn-primary">Nuevo autor</a>
</div>

<table class="table table-striped table-hover">
    <thead>
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Apellido</th>
        <th>Nacionalidad</th>
        <th>Acciones</th>
    </tr>
    </thead>
    <tbody>
    <?php if (!empty($autores)): ?>
        <?php foreach ($autores as $autor): ?>
            <tr>
                <td><?= htmlspecialchars($autor->getIdAutor()) ?></td>
                <td><?= htmlspecialchars($autor->getNombre()) ?></td>
                <td><?= htmlspecialchars($autor->getApellido()) ?></td>
                <td><?= htmlspecialchars($autor->getNacionalidad() ?? '') ?></td>
                <td>
                    <a href="index.php?c=autor&a=edit&id=<?= $autor->getIdAutor() ?>"
                       class="btn btn-sm btn-warning">
                        Editar
                    </a>
                    <a href="index.php?c=autor&a=delete&id=<?= $autor->getIdAutor() ?>"
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('¿Seguro que deseas eliminar este autor?');">
                        Eliminar
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="5" class="text-center">No hay autores registrados.</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>
