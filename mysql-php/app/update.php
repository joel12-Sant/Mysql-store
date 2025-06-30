<?php
session_start();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit;
}

require 'db.php'; 

$id = (int)$_GET['id'] ?? 0;

$stmt = $conexion->prepare("SELECT * FROM playeras WHERE id = ?");
$stmt->execute([$id]);
$playera = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$playera) {
    header("Location: find.php");
    exit;
}

function getNextImageName($dir = 'img/') {
    $files = scandir($dir);
    $numbers = [];

    foreach ($files as $file) {
        if (preg_match('/^(\d+)\.jpg$/', $file, $matches)) {
            $numbers[] = (int)$matches[1];
        }
    }

    $next = empty($numbers) ? 1 : max($numbers) + 1;
    return "$next.jpg";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuevaImagen = $playera['imagen']; 

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $rutaAnterior = 'img/' . $playera['imagen'];
        if (file_exists($rutaAnterior)) {
            unlink($rutaAnterior);
        }

        $nuevoNombre = $id . '.jpg';
        $rutaDestino = 'img/' . $nuevoNombre;
        move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino);
        $nuevaImagen = $nuevoNombre;
    }

    $stmt = $conexion->prepare("UPDATE playeras SET nombre = ?, precio = ?, descripcion = ?, cantidad = ?, imagen = ? WHERE id = ?");
    $stmt->execute([
        $_POST['nombre'],
        $_POST['precio'],
        $_POST['descripcion'],
        $_POST['cantidad'],
        $nuevaImagen,
        $id
    ]);

    header("Location: find.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Editar Playera</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="container mt-4">
  <h1>Editar Playera</h1>
  <form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
      <label class="form-label">Nombre</label>
      <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($playera['nombre']) ?>" required />
    </div>
    <div class="mb-3">
      <label class="form-label">Precio</label>
      <input type="number" step="0.01" name="precio" class="form-control" value="<?= htmlspecialchars($playera['precio']) ?>" required />
    </div>
    <div class="mb-3">
      <label class="form-label">Descripción</label>
      <textarea name="descripcion" class="form-control" rows="3" required><?= htmlspecialchars($playera['descripcion']) ?></textarea>
    </div>
    <div class="mb-3">
      <label class="form-label">Cantidad</label>
      <input type="number" name="cantidad" class="form-control" value="<?= htmlspecialchars($playera['cantidad']) ?>" required />
    </div>
    <div class="mb-3">
      <label class="form-label">Imagen actual</label><br />
      <img src="img/<?= htmlspecialchars($playera['imagen']) ?>" alt="Imagen actual" width="150" />
    </div>
    <div class="mb-3">
      <label class="form-label">Nueva Imagen (opcional)</label>
      <input type="file" name="imagen" accept=".jpg" class="form-control" />
    </div>
    <button type="submit" class="btn btn-primary">Actualizar</button>
    <a href="find.php" class="btn btn-secondary">Cancelar</a>
  </form>
</body>
</html>
