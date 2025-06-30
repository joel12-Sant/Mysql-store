<?php
session_start();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit;
}

require 'db.php';

$buscar_nombre = $_GET['buscar_nombre'] ?? '';
$buscar_precio = $_GET['buscar_precio'] ?? '';

$sql = "SELECT * FROM playeras WHERE 1=1 ";
$params = [];

if (!empty($buscar_nombre)) {
    $sql .= " AND nombre LIKE :nombre ";
    $params['nombre'] = "%$buscar_nombre%";
}

if (!empty($buscar_precio)) {
    $sql .= " AND precio <= :precio ";
    $params['precio'] = $buscar_precio;
}

$sql .= " ORDER BY id ASC";

$stmt = $conexion->prepare($sql);
$stmt->execute($params);
$playeras = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Lista de Playeras</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="container mt-4">
  <h1>Inventario de Playeras</h1>

  <div class="row mb-4">
    <div class="col-md-6">
      <form method="GET" class="d-flex">
        <input
          type="text"
          name="buscar_nombre"
          class="form-control me-2"
          placeholder="Buscar por nombre"
          value="<?= htmlspecialchars($buscar_nombre) ?>"
        />
        <button class="btn btn-outline-primary" type="submit">Buscar</button>
      </form>
    </div>
    <div class="col-md-6">
      <form method="GET" class="d-flex">
        <input
          type="number"
          step="0.01"
          name="buscar_precio"
          class="form-control me-2"
          placeholder="Buscar por precio (<=)"
          value="<?= htmlspecialchars($buscar_precio) ?>"
        />
        <button class="btn btn-outline-secondary" type="submit">Buscar</button>
      </form>
    </div>
  </div>

  <div class="botons-table-agregar">
    <a href="create.php" class="btn btn-primary mb-3">Agregar Playera</a>
    <a href="index.php" class="btn btn-primary mb-3">Ver Tienda</a>
  </div>

  <table class="table table-bordered">
    <thead>
      <tr>
        <th>ID</th><th>Nombre</th><th>Precio</th><th>Descripción</th><th>Stock</th><th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php if (count($playeras) === 0): ?>
        <tr><td colspan="6" class="text-center">No se encontraron playeras.</td></tr>
      <?php else: ?>
        <?php foreach ($playeras as $p): ?>
          <tr>
            <td><?= htmlspecialchars($p['id']) ?></td>
            <td><?= htmlspecialchars($p['nombre']) ?></td>
            <td>$<?= number_format($p['precio'], 2) ?></td>
            <td><?= htmlspecialchars($p['descripcion']) ?></td>
            <td><?= htmlspecialchars($p['cantidad']) ?></td>
            <td>
              <a href="update.php?id=<?= urlencode($p['id']) ?>" class="btn btn-warning btn-sm">Editar</a>
              <a href="delete.php?id=<?= urlencode($p['id']) ?>" class="btn btn-danger btn-sm">Eliminar</a>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>

  <a href="logout.php" class="btn btn-primary mb-3">Cerrar sesión</a>
</body>
</html>
