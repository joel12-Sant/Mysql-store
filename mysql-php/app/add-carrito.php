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

    // Verificar producto y stock
    $stmt = $conexion->prepare("SELECT * FROM playeras WHERE id = ?");
    $stmt->execute([$productoId]);
    $producto = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$producto) {
        throw new Exception("Producto no encontrado.");
    }

    if ((int)$producto['cantidad'] < $cantidad) {
        throw new Exception("No hay suficiente stock disponible.");
    }

    // Verificar si el usuario ya tiene un carrito
    $stmt = $conexion->prepare("SELECT id FROM carritos WHERE usuario_id = ?");
    $stmt->execute([$usuarioId]);
    $carrito = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$carrito) {
        // Crear nuevo carrito
        $stmt = $conexion->prepare("INSERT INTO carritos (usuario_id, fecha_actualizacion) VALUES (?, NOW())");
        $stmt->execute([$usuarioId]);
        $carritoId = $conexion->lastInsertId();
    } else {
        // Usar carrito existente
        $carritoId = $carrito['id'];
        $conexion->prepare("UPDATE carritos SET fecha_actualizacion = NOW() WHERE id = ?")->execute([$carritoId]);
    }

    // Verificar si ya existe un item con la misma talla y producto
    $stmt = $conexion->prepare("SELECT id, cantidad FROM carrito_items WHERE carrito_id = ? AND producto_id = ? AND talla = ?");
    $stmt->execute([$carritoId, $productoId, $talla]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($item) {
        // Ya existe, sumar cantidad
        $nuevaCantidad = $item['cantidad'] + $cantidad;
        $stmt = $conexion->prepare("UPDATE carrito_items SET cantidad = ?, fecha_agregado = NOW() WHERE id = ?");
        $stmt->execute([$nuevaCantidad, $item['id']]);
    } else {
        // Agregar nuevo item
        $stmt = $conexion->prepare("INSERT INTO carrito_items (carrito_id, producto_id, cantidad, talla, fecha_agregado) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([$carritoId, $productoId, $cantidad, $talla]);
    }

    // Actualizar stock en playeras
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
