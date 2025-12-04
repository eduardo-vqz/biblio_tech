<?php

/**
 * @var \App\Models\Libro[] $libros
 * @var array<int,\App\Models\Autor[]> $autoresPorLibro
 */
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Listado de libros</h1>
    <a href="index.php?c=libro&a=create" class="btn btn-primary">
        Nuevo libro
    </a>
</div>

<table class="table table-striped table-hover align-middle">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Título</th>
            <th>ISBN</th>
            <th>Año</th>
            <th>Categoría</th>
            <th>Autores</th>
            <th>Stock (disp./total)</th>
            <th>Estado</th>
            <th style="width: 160px;">Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($libros)): ?>
            <?php foreach ($libros as $libro): ?>
                <?php
                $idLibro   = $libro->getIdLibro();
                $titulo    = $libro->getTitulo();
                $isbn      = $libro->getIsbn();
                $anio      = $libro->getAnioPublicacion();
                $estado    = $libro->getEstado();
                $categoria = $libro->getNombreCategoria() ?? '';

                $stockTotal = $libro->getStockTotal();
                $stockDisp  = $libro->getStockDisponible();

                $autoresTexto = '-';
                if (isset($autoresPorLibro[$idLibro])) {
                    $nombresAutores = [];
                    foreach ($autoresPorLibro[$idLibro] as $autor) {
                        if (method_exists($autor, 'getNombreCompleto')) {
                            $nombresAutores[] = $autor->getNombreCompleto();
                        } else {
                            $nombresAutores[] = trim(
                                ($autor->getNombre() ?? '') . ' ' . ($autor->getApellido() ?? '')
                            );
                        }
                    }
                    if (!empty($nombresAutores)) {
                        $autoresTexto = implode(', ', $nombresAutores);
                    }
                }
                ?>
                <tr>
                    <td><?= htmlspecialchars((string)$idLibro) ?></td>
                    <td><?= htmlspecialchars($titulo) ?></td>
                    <td><?= htmlspecialchars($isbn) ?></td>
                    <td><?= htmlspecialchars((string)$anio) ?></td>
                    <td><?= htmlspecialchars($categoria) ?></td>
                    <td><?= htmlspecialchars($autoresTexto) ?></td>
                    <td><?= htmlspecialchars("$stockDisp / $stockTotal") ?></td>
                    <td><?= htmlspecialchars($estado) ?></td>
                    <td>
                        <a href="index.php?c=libro&a=edit&id=<?= urlencode((string)$idLibro) ?>"
                            class="btn btn-sm btn-warning">
                            Editar
                        </a>
                        <a href="index.php?c=libro&a=delete&id=<?= urlencode((string)$idLibro) ?>"
                            class="btn btn-sm btn-danger"
                            onclick="return confirm('¿Seguro que deseas eliminar este libro?');">
                            Eliminar
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="9" class="text-center">
                    No hay libros registrados.
                </td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>