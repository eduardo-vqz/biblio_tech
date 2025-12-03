<?php /** @var string $viewFile */ ?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Bibliotech</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">Bibliotech</a>
    <div class="navbar-nav">
      <a class="nav-link" href="index.php?c=libro&a=index">Libros</a>
      <a class="nav-link" href="index.php?c=categoria&a=index">Categorías</a>
      <a class="nav-link" href="index.php?c=autor&a=index">Autores</a>
    </div>
  </div>
</nav>

<main class="container mt-4">
  <?php
  if (!isset($viewFile) || empty($viewFile) || !file_exists($viewFile)) {
      echo '<div class="alert alert-danger">Vista no encontrada o no definida.</div>';
  } else {
      include $viewFile;
  }
  ?>
</main>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
