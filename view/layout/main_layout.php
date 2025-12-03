<?php
// Layout principal de Bibliotech
// Se asume que la sesión ya fue iniciada en public/index.php

$usuario = $_SESSION['usuario'] ?? null;
$tipo    = $usuario['tipo'] ?? null;
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Bibliotech</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
          crossorigin="anonymous">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php?c=home&a=index">Bibliotech</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav" aria-controls="navbarNav"
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Menú izquierdo -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <?php if ($usuario): ?>

                    <!-- TODOS LOS ROLES VEN LIBROS -->
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?c=libro&a=index">Libros</a>
                    </li>

                    <!-- ADMIN y BIBLIOTECARIO -->
                    <?php if ($tipo === 'ADMIN' || $tipo === 'BIBLIOTECARIO'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?c=categoria&a=index">Categorías</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="index.php?c=autor&a=index">Autores</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="index.php?c=usuario&a=index">Usuarios</a>
                        </li>
                    <?php endif; ?>

                    <!-- TODOS LOS ROLES VEN PRÉSTAMOS (con lógica de permisos en el controlador) -->
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?c=prestamo&a=index">Préstamos</a>
                    </li>

                <?php endif; ?>

            </ul>

            <!-- Menú derecho (usuario / login) -->
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <?php if ($usuario): ?>
                    <li class="nav-item">
                        <span class="navbar-text me-3">
                            <?= htmlspecialchars($usuario['nombre']) ?>
                            (<?= htmlspecialchars($usuario['tipo']) ?>)
                        </span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?c=auth&a=logout">Cerrar sesión</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?c=auth&a=login">Iniciar sesión</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<main class="container mb-4">
    <?php
    // Carga de la vista específica
    if (!isset($viewFile) || empty($viewFile) || !file_exists($viewFile)) {
        echo '<div class="alert alert-danger">Vista no encontrada o no definida.</div>';
    } else {
        include $viewFile;
    }
    ?>
</main>

<footer class="bg-light text-center py-3 mt-auto border-top">
    <small class="text-muted">
        Bibliotech &copy; <?= date('Y') ?> - Proyecto académico en PHP
    </small>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

</body>
</html>
