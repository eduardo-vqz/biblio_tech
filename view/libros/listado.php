<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3">Libros</h1>
    <a href="index.php?c=libro&a=create" class="btn btn-primary">Nuevo libro</a>
</div>

<table class="table table-striped table-hover">
    <thead>
    <tr>
        <th>ID</th>
        <th>Título</th>
        <th>Categoría</th>
        <th>Stock</th>
        <th>Disponible</th>
        <th>Estado</th>
    </tr>
    </thead>
    <tbody>
    <?php if (!empty($libros)): ?>
        <?php foreach ($libros as $libro): ?>
            <tr>
                <td><?= htmlspecialchars($libro->getIdLibro()) ?></td>
                <td><?= htmlspecialchars($libro->getTitulo()) ?></td>
                <td><?= htmlspecialchars($libro->getCategoria()?->getNombre() ?? '-') ?></td>
                <td><?= htmlspecialchars($libro->getStockTotal()) ?></td>
                <td><?= htmlspecialchars($libro->getStockDisponible()) ?></td>
                <td><?= htmlspecialchars($libro->getEstado()) ?></td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="6" class="text-center">No hay libros registrados.</td></tr>
    <?php endif; ?>
    </tbody>
</table>
