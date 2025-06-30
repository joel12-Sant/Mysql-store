<?php
session_start();
require 'db.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_SESSION['add_to_cart'])) {
    $_SESSION['error'] = "Datos del producto no recibidos.";
    header("Location: index.php");
    exit;
}

try {
    $usuarioId = (int)$_SESSION['usuario_id'];
    $productoId = (int)$_SESSION['add_to_cart']['producto_id'];
    $cantidad = (int)$_SESSION['add_to_cart']['cantidad'];
    $talla = trim($_SESSION['add_to_cart']['talla']);

    $stmt = $conexion->prepare("SELECT * FROM playeras WHERE id = ?");
    $stmt->execute([$productoId]);
    $producto = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$producto) {
        throw new Exception("Producto no encontrado.");
    }

    if ((int)$producto['cantidad'] < $cantidad) {
        throw new Exception("No hay suficiente stock disponible.");
    }

    $stmt = $conexion->prepare("SELECT id FROM carritos WHERE usuario_id = ?");
    $stmt->execute([$usuarioId]);
    $carrito = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$carrito) {
        $stmt = $conexion->prepare("INSERT INTO carritos (usuario_id, fecha_actualizacion) VALUES (?, NOW())");
        $stmt->execute([$usuarioId]);
        $carritoId = $conexion->lastInsertId();
    } else {
        $carritoId = $carrito['id'];
        $conexion->prepare("UPDATE carritos SET fecha_actualizacion = NOW() WHERE id = ?")->execute([$carritoId]);
    }

    $stmt = $conexion->prepare("SELECT id, cantidad FROM carrito_items WHERE carrito_id = ? AND producto_id = ? AND talla = ?");
    $stmt->execute([$carritoId, $productoId, $talla]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($item) {
        $nuevaCantidad = $item['cantidad'] + $cantidad;
        $stmt = $conexion->prepare("UPDATE carrito_items SET cantidad = ?, fecha_agregado = NOW() WHERE id = ?");
        $stmt->execute([$nuevaCantidad, $item['id']]);
    } else {
        $stmt = $conexion->prepare("INSERT INTO carrito_items (carrito_id, producto_id, cantidad, talla, fecha_agregado) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([$carritoId, $productoId, $cantidad, $talla]);
    }

    $stmt = $conexion->prepare("UPDATE playeras SET cantidad = cantidad - ? WHERE id = ?");
    $stmt->execute([$cantidad, $productoId]);

    unset($_SESSION['add_to_cart']);
    $_SESSION['exito'] = "¡Producto agregado al carrito!";
    header("Location: carrito.php");
    exit;

} catch (Exception $e) {
    $_SESSION['error'] = "Error: " . $e->getMessage();
    header("Location: producto.php?id=" . $_SESSION['add_to_cart']['producto_id']);
    exit;
}
