<?php
require 'db.php';

$sql = "SELECT * FROM playeras";
$result = $pdo->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Lista de Playeras</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h1 class="mb-4">Lista de Playeras</h1>
  <a href="create.php" class="btn btn-success mb-3">Agregar nueva playera</a>

  <table class="table table-striped">
    <thead>
      <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Descripción</th>
        <th>Precio</th>
        <th>Imagen</th>
        <th>Stock</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $result->fetch()): ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['nombre']) ?></td>
        <td><?= htmlspecialchars($row['descripcion']) ?></td>
        <td>$<?= number_format($row['precio'], 2) ?></td>
        <td>
          <?php if ($row['imagen']): ?>
            <img src="img/<?= htmlspecialchars($row['imagen']) ?>" alt="Imagen" style="width: 50px;">
          <?php else: ?>
            Sin imagen
          <?php endif; ?>
        </td>
        <td><?= $row['stock'] ?></td>
        <td>
          <a href="update.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
          <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que quieres eliminar esta playera?');">Eliminar</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
</body>
</html>
