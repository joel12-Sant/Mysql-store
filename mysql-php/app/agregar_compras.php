<?php
session_start();
require 'db.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

try {
    $usuarioId = $_SESSION['usuario_id'];
    $productosCompra = [];
    $total = 0;

    // Obtener ID del carrito del usuario
    $stmt = $conexion->prepare("SELECT id FROM carritos WHERE usuario_id = ?");
    $stmt->execute([$usuarioId]);
    $carrito = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$carrito) {
        throw new Exception("No se encontró el carrito.");
    }

    $carritoId = $carrito['id'];

    // Compra individual o total
    if (isset($_POST['item_id'])) {
        $itemId = $_POST['item_id'];

        $stmt = $conexion->prepare("SELECT ci.*, p.precio, p.nombre FROM carrito_items ci
                                    JOIN playeras p ON ci.producto_id = p.id
                                    WHERE ci.id = ? AND ci.carrito_id = ?");
        $stmt->execute([$itemId, $carritoId]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$item) {
            throw new Exception("Producto no encontrado en el carrito.");
        }

        $subtotal = $item['precio'] * $item['cantidad'];
        $total += $subtotal;

        $productosCompra[] = [
            'playera_id' => $item['producto_id'],
            'talla' => $item['talla'],
            'cantidad' => $item['cantidad'],
            'precio_unitario' => $item['precio'],
            'item_id' => $item['id']
        ];

    } else {
        // Todos los productos del carrito
        $stmt = $conexion->prepare("SELECT ci.*, p.precio, p.nombre FROM carrito_items ci
                                    JOIN playeras p ON ci.producto_id = p.id
                                    WHERE ci.carrito_id = ?");
        $stmt->execute([$carritoId]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$items) {
            throw new Exception("El carrito está vacío.");
        }

        foreach ($items as $item) {
            $subtotal = $item['precio'] * $item['cantidad'];
            $total += $subtotal;

            $productosCompra[] = [
                'playera_id' => $item['producto_id'],
                'talla' => $item['talla'],
                'cantidad' => $item['cantidad'],
                'precio_unitario' => $item['precio'],
                'item_id' => $item['id']
            ];
        }
    }

    if (empty($productosCompra)) {
        throw new Exception("No hay productos válidos para comprar.");
    }

    // Insertar compra
    $stmt = $conexion->prepare("INSERT INTO compras (usuario_id, total) VALUES (?, ?)");
    $stmt->execute([$usuarioId, $total]);
    $compraId = $conexion->lastInsertId();

    // Insertar productos
    $stmtItem = $conexion->prepare("INSERT INTO compra_items (compra_id, playera_id, talla, cantidad, precio_unitario)
                                    VALUES (?, ?, ?, ?, ?)");

    foreach ($productosCompra as $producto) {
        $stmtItem->execute([
            $compraId,
            $producto['playera_id'],
            $producto['talla'],
            $producto['cantidad'],
            $producto['precio_unitario']
        ]);
    }

    // Eliminar del carrito
    if (isset($_POST['item_id'])) {
        $stmt = $conexion->prepare("DELETE FROM carrito_items WHERE id = ? AND carrito_id = ?");
        $stmt->execute([$productosCompra[0]['item_id'], $carritoId]);
    } else {
        $stmt = $conexion->prepare("DELETE FROM carrito_items WHERE carrito_id = ?");
        $stmt->execute([$carritoId]);
    }

    $_SESSION['mensaje_exito'] = "¡Compra realizada con éxito!";
    header("Location: compras.php");
    exit;

} catch (Exception $e) {
    echo "Error al procesar la compra: " . $e->getMessage();
}
