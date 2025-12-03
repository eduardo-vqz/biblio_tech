<?php
/** @var \App\Models\Autor $autor */
$esEdicion = $autor->getIdAutor() !== null;
$action = $esEdicion ? 'update' : 'store';
?>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <h1 class="h3 mb-3">
            <?= $esEdicion ? 'Editar autor' : 'Nuevo autor' ?>
        </h1>

        <form action="index.php?c=autor&a=<?= $action ?>" method="post">
            <?php if ($esEdicion): ?>
                <input type="hidden" name="id_autor"
                       value="<?= htmlspecialchars($autor->getIdAutor()) ?>">
            <?php endif; ?>

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text"
                       id="nombre"
                       name="nombre"
                       class="form-control"
                       required
                       value="<?= htmlspecialchars($autor->getNombre()) ?>">
            </div>

            <div class="mb-3">
                <label for="apellido" class="form-label">Apellido</label>
                <input type="text"
                       id="apellido"
                       name="apellido"
                       class="form-control"
                       required
                       value="<?= htmlspecialchars($autor->getApellido()) ?>">
            </div>

            <div class="mb-3">
                <label for="nacionalidad" class="form-label">Nacionalidad</label>
                <input type="text"
                       id="nacionalidad"
                       name="nacionalidad"
                       class="form-control"
                       value="<?= htmlspecialchars($autor->getNacionalidad() ?? '') ?>">
            </div>

            <div class="d-flex justify-content-between">
                <a href="index.php?c=autor&a=index" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-success">
                    <?= $esEdicion ? 'Actualizar' : 'Guardar' ?>
                </button>
            </div>
        </form>
    </div>
</div>
