<?php

/**
 * @var \App\Models\Libro $libro
 * @var \App\Models\Categoria[] $categorias
 * @var \App\Models\Autor[] $autores
 * @var int[] $autoresSel
 */

$esEdicion = $libro && $libro->getIdLibro() !== null;
$action = $esEdicion ? 'update' : 'store';

$idLibro         = $esEdicion ? $libro->getIdLibro() : null;
$titulo          = $esEdicion ? $libro->getTitulo() : '';
$isbn            = $esEdicion ? $libro->getIsbn() : '';
$anioPublicacion = $esEdicion ? $libro->getAnioPublicacion() : '';
$idCategoria     = $esEdicion ? $libro->getIdCategoria() : 0;
$descripcion     = $esEdicion ? ($libro->getDescripcion() ?? '') : '';
$stockTotal      = $esEdicion ? $libro->getStockTotal() : 0;
$stockDisp       = $esEdicion ? $libro->getStockDisponible() : 0;
$estado          = $esEdicion ? $libro->getEstado() : 'DISPONIBLE';
?>

<div class="row">
    <div class="col-md-10 offset-md-1 col-lg-8 offset-lg-2">

        <h1 class="h3 mb-3">
            <?= $esEdicion ? 'Editar libro' : 'Nuevo libro' ?>
        </h1>

        <form action="index.php?c=libro&a=<?= $action ?>" method="post">

            <?php if ($esEdicion): ?>
                <input type="hidden" name="id_libro"
                    value="<?= htmlspecialchars((string)$idLibro) ?>">
            <?php endif; ?>

            <!-- Título -->
            <div class="mb-3">
                <label for="titulo" class="form-label">Título</label>
                <input type="text"
                    id="titulo"
                    name="titulo"
                    class="form-control"
                    required
                    value="<?= htmlspecialchars($titulo) ?>">
            </div>

            <!-- ISBN -->
            <div class="mb-3">
                <label for="isbn" class="form-label">ISBN</label>
                <input type="text"
                    id="isbn"
                    name="isbn"
                    class="form-control"
                    value="<?= htmlspecialchars($isbn) ?>">
            </div>

            <!-- Año de publicación -->
            <div class="mb-3">
                <label for="anio_publicacion" class="form-label">Año de publicación</label>
                <input type="number"
                    id="anio_publicacion"
                    name="anio_publicacion"
                    class="form-control"
                    min="0"
                    max="<?= date('Y') ?>"
                    value="<?= htmlspecialchars($anioPublicacion) ?>">
            </div>

            <!-- Categoría -->
            <div class="mb-3">
                <label for="id_categoria" class="form-label">Categoría</label>
                <select id="id_categoria" name="id_categoria" class="form-select" required>
                    <option value="">-- Seleccione categoría --</option>
                    <?php foreach ($categorias as $cat): ?>
                        <?php
                        $selected = $cat->getIdCategoria() == $idCategoria ? 'selected' : '';
                        ?>
                        <option value="<?= $cat->getIdCategoria() ?>" <?= $selected ?>>
                            <?= htmlspecialchars($cat->getNombre()) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Autores -->
            <div class="mb-3">
                <label for="autores" class="form-label">Autores</label>
                <select name="autores[]" id="autores" class="form-select" multiple size="6">
                    <?php foreach ($autores as $a): ?>
                        <?php
                        $idAutor = $a->getIdAutor();
                        $selected = in_array($idAutor, $autoresSel ?? [], true) ? 'selected' : '';
                        $nombreAutor = method_exists($a, 'getNombreCompleto')
                            ? $a->getNombreCompleto()
                            : ($a->getNombre() . ' ' . $a->getApellido());
                        ?>
                        <option value="<?= $idAutor ?>" <?= $selected ?>>
                            <?= htmlspecialchars($nombreAutor) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="form-text">
                    Mantén presionada la tecla Ctrl (o Cmd en Mac) para seleccionar varios autores.
                </div>
            </div>

            <!-- Descripción -->
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea id="descripcion" name="descripcion" rows="3"
                    class="form-control"><?= htmlspecialchars($descripcion) ?></textarea>
            </div>

            <!-- Stock total -->
            <div class="mb-3">
                <label for="stock_total" class="form-label">Stock total</label>
                <input type="number"
                    id="stock_total"
                    name="stock_total"
                    class="form-control"
                    min="0"
                    required
                    value="<?= htmlspecialchars($stockTotal) ?>">
            </div>

            <!-- Stock disponible -->
            <div class="mb-3">
                <label for="stock_disponible" class="form-label">Stock disponible</label>
                <input type="number"
                    id="stock_disponible"
                    name="stock_disponible"
                    class="form-control"
                    min="0"
                    required
                    value="<?= htmlspecialchars($stockDisp) ?>">
            </div>

            <!-- Estado -->
            <div class="mb-3">
                <label for="estado" class="form-label">Estado</label>
                <select name="estado" id="estado" class="form-select">
                    <option value="DISPONIBLE" <?= $estado === 'DISPONIBLE' ? 'selected' : '' ?>>
                        Disponible
                    </option>
                    <option value="PRESTADO" <?= $estado === 'PRESTADO' ? 'selected' : '' ?>>
                        Prestado
                    </option>
                    <option value="NO_DISPONIBLE" <?= $estado === 'NO_DISPONIBLE' ? 'selected' : '' ?>>
                        No disponible
                    </option>
                </select>
            </div>

            <!-- Botones -->
            <div class="d-flex justify-content-between mt-4">
                <a href="index.php?c=libro&a=index" class="btn btn-secondary">
                    Cancelar
                </a>
                <button type="submit" class="btn btn-success">
                    <?= $esEdicion ? 'Actualizar libro' : 'Guardar libro' ?>
                </button>
            </div>
        </form>
    </div>
</div>