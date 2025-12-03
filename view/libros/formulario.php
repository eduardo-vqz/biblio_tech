<?php
// $libro y $categorias vienen desde el controlador

$esEdicion = $libro->getIdLibro() !== null;
$action = $esEdicion ? 'update' : 'store';
?>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <h1 class="h3 mb-3">
            <?= $esEdicion ? 'Editar libro' : 'Nuevo libro' ?>
        </h1>

        <form action="index.php?c=libro&a=<?= $action ?>" method="post">
            <?php if ($esEdicion): ?>
                <input type="hidden" name="id_libro" value="<?= htmlspecialchars($libro->getIdLibro()) ?>">
            <?php endif; ?>

            <div class="mb-3">
                <label for="titulo" class="form-label">Título</label>
                <input type="text"
                       id="titulo"
                       name="titulo"
                       class="form-control"
                       required
                       value="<?= htmlspecialchars($libro->getTitulo()) ?>">
            </div>

            <div class="mb-3">
                <label for="isbn" class="form-label">ISBN</label>
                <input type="text"
                       id="isbn"
                       name="isbn"
                       class="form-control"
                       value="<?= htmlspecialchars($libro->getIsbn() ?? '') ?>">
            </div>

            <div class="mb-3">
                <label for="anio_publicacion" class="form-label">Año de publicación</label>
                <input type="number"
                       id="anio_publicacion"
                       name="anio_publicacion"
                       class="form-control"
                       min="1500" max="<?= date('Y') + 1 ?>"
                       value="<?= htmlspecialchars($libro->getAnioPublicacion() ?? '') ?>">
            </div>

            <div class="mb-3">
                <label for="id_categoria" class="form-label">Categoría</label>
                <select id="id_categoria" name="id_categoria" class="form-select">
                    <option value="">-- Seleccione --</option>
                    <?php foreach ($categorias as $cat): ?>
                        <option value="<?= $cat->getIdCategoria() ?>"
                            <?= $libro->getIdCategoria() === $cat->getIdCategoria() ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat->getNombre()) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="stock_total" class="form-label">Stock total</label>
                <input type="number"
                       id="stock_total"
                       name="stock_total"
                       class="form-control"
                       min="1"
                       value="<?= htmlspecialchars($libro->getStockTotal() ?: 1) ?>">
            </div>

            <?php if ($esEdicion): ?>
                <div class="mb-3">
                    <label for="stock_disponible" class="form-label">Stock disponible</label>
                    <input type="number"
                           id="stock_disponible"
                           name="stock_disponible"
                           class="form-control"
                           min="0"
                           value="<?= htmlspecialchars($libro->getStockDisponible()) ?>">
                </div>
            <?php endif; ?>

            <div class="mb-3">
                <label for="estado" class="form-label">Estado</label>
                <select id="estado" name="estado" class="form-select">
                    <?php
                    $estados = ['DISPONIBLE', 'NO_DISPONIBLE'];
                    foreach ($estados as $estado):
                    ?>
                        <option value="<?= $estado ?>"
                            <?= $libro->getEstado() === $estado ? 'selected' : '' ?>>
                            <?= $estado ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea id="descripcion"
                          name="descripcion"
                          class="form-control"
                          rows="3"><?= htmlspecialchars($libro->getDescripcion() ?? '') ?></textarea>
            </div>

            <div class="d-flex justify-content-between">
                <a href="index.php?c=libro&a=index" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-success">
                    <?= $esEdicion ? 'Actualizar' : 'Guardar' ?>
                </button>
            </div>
        </form>
    </div>
</div>
