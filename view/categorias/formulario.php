<?php
/** @var \App\Models\Categoria $categoria */
$esEdicion = $categoria->getIdCategoria() !== null;
$action = $esEdicion ? 'update' : 'store';
?>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <h1 class="h3 mb-3">
            <?= $esEdicion ? 'Editar categoría' : 'Nueva categoría' ?>
        </h1>

        <form action="index.php?c=categoria&a=<?= $action ?>" method="post">
            <?php if ($esEdicion): ?>
                <input type="hidden" name="id_categoria"
                       value="<?= htmlspecialchars($categoria->getIdCategoria()) ?>">
            <?php endif; ?>

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text"
                       id="nombre"
                       name="nombre"
                       class="form-control"
                       required
                       value="<?= htmlspecialchars($categoria->getNombre()) ?>">
            </div>

            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea id="descripcion"
                          name="descripcion"
                          class="form-control"
                          rows="3"><?= htmlspecialchars($categoria->getDescripcion() ?? '') ?></textarea>
            </div>

            <div class="d-flex justify-content-between">
                <a href="index.php?c=categoria&a=index" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-success">
                    <?= $esEdicion ? 'Actualizar' : 'Guardar' ?>
                </button>
            </div>
        </form>
    </div>
</div>
