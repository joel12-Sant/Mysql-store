<?php
session_start();
require 'db.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

try {
    $usuarioId = (int)$_SESSION['usuario_id'];

    $stmtCarrito = $conexion->prepare("SELECT id FROM carritos WHERE usuario_id = ?");
    $stmtCarrito->execute([$usuarioId]);
    $carrito = $stmtCarrito->fetch(PDO::FETCH_ASSOC);

    if ($carrito) {
        $carritoId = $carrito['id'];

        $conexion->prepare("DELETE FROM carrito_items WHERE carrito_id = ?")->execute([$carritoId]);

        $conexion->prepare("DELETE FROM carritos WHERE id = ?")->execute([$carritoId]);
    }

    $stmtCompras = $conexion->prepare("SELECT id FROM compras WHERE usuario_id = ?");
    $stmtCompras->execute([$usuarioId]);
    $compras = $stmtCompras->fetchAll(PDO::FETCH_ASSOC);

    foreach ($compras as $compra) {
        $compraId = $compra['id'];

        $conexion->prepare("DELETE FROM compra_items WHERE compra_id = ?")->execute([$compraId]);

        $conexion->prepare("DELETE FROM compras WHERE id = ?")->execute([$compraId]);
    }

    $conexion->prepare("DELETE FROM usuarios WHERE id = ?")->execute([$usuarioId]);
    
    session_destroy();
    session_start();
    $_SESSION['mensaje_exito'] = "Tu cuenta ha sido eliminada correctamente.";
    header("Location: index.php");
    exit;

} catch (Exception $e) {
    echo "Error al eliminar la cuenta: " . $e->getMessage();
}
