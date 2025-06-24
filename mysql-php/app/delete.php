<?php
require 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Obtener nombre de la imagen
    $stmt = $pdo->prepare("SELECT imagen FROM playeras WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $row = $stmt->fetch();

    if ($row && !empty($row['imagen'])) {
        // Si el nombre de imagen ya incluye "img/", no concatenar dos veces
        $ruta = (strpos($row['imagen'], 'img/') === 0) ? $row['imagen'] : "img/" . $row['imagen'];

        if (file_exists($ruta)) {
            unlink($ruta); // borrar imagen física
        }
    }

    $stmt = $pdo->prepare("DELETE FROM playeras WHERE id = :id");
    $stmt->execute(['id' => $id]);
}

header("Location: find.php");
exit();
?>
