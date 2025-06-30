<?php
session_start();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit;
}

require 'db.php'; 

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    $stmt = $conexion->prepare("SELECT imagen FROM playeras WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $playera = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($playera && !empty($playera['imagen'])) {
        $imagenRuta = __DIR__ . "/img/{$id}.jpg";
        if (file_exists($imagenRuta)) {
            unlink($imagenRuta);
        }
    }

    $stmt = $conexion->prepare("DELETE FROM playeras WHERE id = :id");
    $stmt->execute(['id' => $id]);
}

header("Location: find.php");
exit;
?>
