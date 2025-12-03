<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3">Categorías</h1>
    <a href="index.php?c=categoria&a=create" class="btn btn-primary">Nueva categoría</a>
</div>

<table class="table table-striped table-hover">
    <thead>
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Descripción</th>
        <th>Acciones</th>
    </tr>
    </thead>
    <tbody>
    <?php if (!empty($categorias)): ?>
        <?php foreach ($categorias as $cat): ?>
            <tr>
                <td><?= htmlspecialchars($cat->getIdCategoria()) ?></td>
                <td><?= htmlspecialchars($cat->getNombre()) ?></td>
                <td><?= htmlspecialchars($cat->getDescripcion() ?? '') ?></td>
                <td>
                    <a href="index.php?c=categoria&a=edit&id=<?= $cat->getIdCategoria() ?>"
                       class="btn btn-sm btn-warning">
                        Editar
                    </a>
                    <a href="index.php?c=categoria&a=delete&id=<?= $cat->getIdCategoria() ?>"
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('¿Seguro que deseas eliminar esta categoría?');">
                        Eliminar
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="4" class="text-center">No hay categorías registradas.</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>
