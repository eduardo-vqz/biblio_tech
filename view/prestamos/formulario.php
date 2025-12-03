<?php
/** @var \App\Models\Prestamo $prestamo */
/** @var \App\Models\Usuario[] $usuarios */
/** @var \App\Models\Libro[] $libros */

$esEdicion = $prestamo->getIdPrestamo() !== null;
$action    = $esEdicion ? 'update' : 'store';

$estados = ['PRESTADO', 'DEVUELTO', 'ATRASADO', 'CANCELADO'];
?>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <h1 class="h3 mb-3">
            <?= $esEdicion ? 'Editar préstamo' : 'Nuevo préstamo' ?>
        </h1>

        <form action="index.php?c=prestamo&a=<?= $action ?>" method="post">
            <?php if ($esEdicion): ?>
                <input type="hidden" name="id_prestamo"
                       value="<?= htmlspecialchars($prestamo->getIdPrestamo()) ?>">
            <?php endif; ?>

            <div class="mb-3">
                <label for="id_usuario" class="form-label">Usuario</label>
                <select id="id_usuario" name="id_usuario" class="form-select" required>
                    <option value="">-- Seleccione usuario --</option>
                    <?php foreach ($usuarios as $u): ?>
                        <option value="<?= $u->getIdUsuario() ?>"
                            <?= $prestamo->getIdUsuario() === $u->getIdUsuario() ? 'selected' : '' ?>>
                            <?= htmlspecialchars($u->getNombreCompleto() . ' (' . $u->getEmail() . ')') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="id_libro" class="form-label">Libro</label>
                <select id="id_libro" name="id_libro" class="form-select" required>
                    <option value="">-- Seleccione libro --</option>
                    <?php foreach ($libros as $l): ?>
                        <option value="<?= $l->getIdLibro() ?>"
                            <?= $prestamo->getIdLibro() === $l->getIdLibro() ? 'selected' : '' ?>>
                            <?= htmlspecialchars($l->getTitulo()) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="fecha_prestamo" class="form-label">Fecha de préstamo</label>
                <input type="date"
                       id="fecha_prestamo"
                       name="fecha_prestamo"
                       class="form-control"
                       required
                       value="<?= htmlspecialchars($prestamo->getFechaPrestamo()) ?>">
            </div>

            <div class="mb-3">
                <label for="fecha_devolucion" class="form-label">Fecha de devolución (límite)</label>
                <input type="date"
                       id="fecha_devolucion"
                       name="fecha_devolucion"
                       class="form-control"
                       required
                       value="<?= htmlspecialchars($prestamo->getFechaDevolucion()) ?>">
            </div>

            <?php if ($esEdicion): ?>
                <div class="mb-3">
                    <label for="fecha_devuelto" class="form-label">Fecha devuelto (real)</label>
                    <input type="date"
                           id="fecha_devuelto"
                           name="fecha_devuelto"
                           class="form-control"
                           value="<?= htmlspecialchars($prestamo->getFechaDevuelto() ?? '') ?>">
                </div>

                <div class="mb-3">
                    <label for="estado" class="form-label">Estado</label>
                    <select id="estado" name="estado" class="form-select">
                        <?php foreach ($estados as $estado): ?>
                            <option value="<?= $estado ?>"
                                <?= $prestamo->getEstado() === $estado ? 'selected' : '' ?>>
                                <?= $estado ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endif; ?>

            <div class="mb-3">
                <label for="observaciones" class="form-label">Observaciones</label>
                <textarea id="observaciones"
                          name="observaciones"
                          class="form-control"
                          rows="3"><?= htmlspecialchars($prestamo->getObservaciones() ?? '') ?></textarea>
            </div>

            <div class="d-flex justify-content-between">
                <a href="index.php?c=prestamo&a=index" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-success">
                    <?= $esEdicion ? 'Actualizar' : 'Guardar' ?>
                </button>
            </div>
        </form>
    </div>
</div>
