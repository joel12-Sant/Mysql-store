<?php
session_start();
require 'db.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

if (
    $_SERVER['REQUEST_METHOD'] !== 'POST' ||
    !isset($_POST['item_id'], $_POST['producto_id'], $_POST['cantidad'])
) {
    $_SESSION['error'] = "Solicitud inválida";
    header("Location: carrito.php");
    exit;
}

try {
    $usuarioId = (int)$_SESSION['usuario_id'];
    $itemId = (int)$_POST['item_id'];
    $productoId = (int)$_POST['producto_id'];
    $cantidad = (int)$_POST['cantidad'];

    // Verificar si el carrito pertenece al usuario
    $stmt = $conexion->prepare("SELECT id FROM carritos WHERE usuario_id = ?");
    $stmt->execute([$usuarioId]);
    $carrito = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$carrito) {
        throw new Exception("Carrito no encontrado");
    }

    // Eliminar el item del carrito_items
    $stmt = $conexion->prepare("DELETE FROM carrito_items WHERE id = ? AND carrito_id = ?");
    $stmt->execute([$itemId, $carrito['id']]);

    // Restaurar inventario del producto
    $stmt = $conexion->prepare("UPDATE playeras SET cantidad = cantidad + ? WHERE id = ?");
    $stmt->execute([$cantidad, $productoId]);

    $_SESSION['exito'] = "Producto eliminado del carrito";
} catch (Exception $e) {
    $_SESSION['error'] = "Error al eliminar: " . $e->getMessage();
}

header("Location: carrito.php");
exit;
