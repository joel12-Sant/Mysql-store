<?php
require 'db.php';

if (!isset($_GET['id'])) {
    header("Location: find.php");
    exit();
}

$id = $_GET['id'];

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

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];

    // Primero obtener la imagen anterior para borrarla si hay nueva imagen
    $stmt = $pdo->prepare("SELECT imagen FROM playeras WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $playera = $stmt->fetch();

    if (!empty($_FILES['imagen']['name']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        // Borrar imagen anterior si existe
        if (!empty($playera['imagen'])) {
            $rutaAnterior = "img/" . $playera['imagen'];
            if (file_exists($rutaAnterior)) {
                unlink($rutaAnterior);
            }  
        // Generar nuevo nombre secuencial para la imagen
        $nuevoNombre = getNextImageName();
        // Mover archivo a carpeta img/
        move_uploaded_file($_FILES['imagen']['tmp_name'], "img/$nuevoNombre");
        }

        // Actualizar con nueva imagen
        $stmt = $pdo->prepare("UPDATE playeras SET nombre = :nombre, descripcion = :descripcion, precio = :precio, imagen = :imagen, stock = :stock WHERE id = :id");
        $stmt->execute([
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'precio' => $precio,
            'imagen' => $nuevoNombre,
            'stock' => $stock,
            'id' => $id
        ]);
    } else {
        // Actualizar sin cambiar imagen
        $stmt = $pdo->prepare("UPDATE playeras SET nombre = :nombre, descripcion = :descripcion, precio = :precio, stock = :stock WHERE id = :id");
        $stmt->execute([
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'precio' => $precio,
            'stock' => $stock,
            'id' => $id
        ]);
    }

    header("Location: find.php");
    exit();
}

// Obtener datos actuales
$stmt = $pdo->prepare("SELECT * FROM playeras WHERE id = :id");
$stmt->execute(['id' => $id]);
$playera = $stmt->fetch();

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Playera</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h1>Editar playera</h1>
  <form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
      <label>Nombre</label>
      <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($playera['nombre']) ?>" required>
    </div>
    <div class="mb-3">
      <label>Descripción</label>
      <textarea name="descripcion" class="form-control"><?= htmlspecialchars($playera['descripcion']) ?></textarea>
    </div>
    <div class="mb-3">
      <label>Precio</label>
      <input type="number" step="0.01" name="precio" class="form-control" value="<?= $playera['precio'] ?>" required>
    </div>
    <div class="mb-3">
      <label>Imagen actual</label><br>
      <?php if ($playera['imagen']): ?>
        <img src="img/<?= htmlspecialchars($playera['imagen']) ?>" style="width: 70px;"><br>
      <?php endif; ?>
      <input type="file" name="imagen" class="form-control mt-2" accept=".jpg">
    </div>
    <div class="mb-3">
      <label>Stock</label>
      <input type="number" name="stock" class="form-control" value="<?= $playera['stock'] ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Actualizar</button>
    <a href="find.php" class="btn btn-secondary">Cancelar</a>
  </form>
</div>
</body>
</html>
