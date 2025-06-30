<?php
session_start();
require 'db.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$mensaje = null;
$itemsCarrito = [];
$total = 0;

try {
    $usuarioId = (int)$_SESSION['usuario_id'];

    $stmt = $conexion->prepare("SELECT id FROM carritos WHERE usuario_id = ?");
    $stmt->execute([$usuarioId]);
    $carrito = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$carrito) {
        $mensaje = "Tu carrito está vacío.";
    } else {
        $carritoId = $carrito['id'];

        $stmt = $conexion->prepare("
            SELECT ci.id AS item_id, ci.producto_id, ci.cantidad, ci.talla, ci.fecha_agregado,
                   p.nombre, p.precio, p.imagen
            FROM carrito_items ci
            INNER JOIN playeras p ON ci.producto_id = p.id
            WHERE ci.carrito_id = ?
        ");
        $stmt->execute([$carritoId]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($items)) {
            $mensaje = "Tu carrito está vacío.";
        } else {
            foreach ($items as $item) {
                $subtotal = $item['precio'] * $item['cantidad'];
                $total += $subtotal;

                $itemsCarrito[] = [
                    'item_id' => $item['item_id'],
                    'producto_id' => $item['producto_id'],
                    'nombre' => $item['nombre'],
                    'precio' => $item['precio'],
                    'imagen' => $item['imagen'],
                    'cantidad' => $item['cantidad'],
                    'talla' => $item['talla'],
                    'subtotal' => $subtotal
                ];
            }
        }
    }

} catch (Exception $e) {
    $mensaje = "Error al cargar el carrito: " . $e->getMessage();
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Carrito de Compras - FrontEnd Store</title>
    <link rel="stylesheet" href="css/normalize.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Krub:wght@300;700&family=Staatliches&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="/css/styles.css?v=<?= time() ?>" />
</head>
<body>
    <header class="header">
        <a href="index.php" style="display: block; text-align: center;">
            <img class="header__logo" src="img/logo.png" alt="Logotipo" />
        </a>

        <div class="right-buttons">
            <?php if(isset($_SESSION['usuario_id'])): ?>
                <a href="logout.php" class="auth-button">Cerrar Sesión</a>
            <?php else: ?>
                <a href="login.php" class="auth-button">Iniciar Sesión</a>
            <?php endif; ?>
        </div>
    </header>

    <nav class="navegacion">
        <?php if (isset($_SESSION['usuario_id'])): ?>
            <a class="navegacion__enlace" href="mi-perfil.php">Mi Perfil</a>
        <?php endif; ?>
        <a class="navegacion__enlace" href="index.php">Tienda</a>
        <a class="navegacion__enlace navegacion__enlace--activo" href="carrito.php">Carrito</a>
        <a class="navegacion__enlace" href="nosotros.php">Nosotros</a>
    </nav>

    <main class="contenedor">
        <h1>Tu Carrito de Compras</h1>

        <?php if (isset($mensaje)): ?>
            <p class="carrito-vacio"><?php echo htmlspecialchars($mensaje); ?></p>
            <a href="index.php" class="boton">Seguir Comprando</a>
        <?php else: ?>
            <div class="carrito-items">
                <?php foreach ($itemsCarrito as $item): ?>
                    <div class="carrito-item">
                        <img class="carrito-item-img" src="img/<?php echo htmlspecialchars($item['imagen']); ?>" alt="<?php echo htmlspecialchars($item['nombre']); ?>">
                        <div class="carrito-item-info">
                            <h4><?php echo htmlspecialchars($item['nombre']); ?></h4>
                            <p>Talla: <?php echo htmlspecialchars($item['talla']); ?></p>
                            <p>Cantidad: <?php echo $item['cantidad']; ?></p>
                            <p>Precio unitario: $<?php echo number_format($item['precio'], 2); ?></p>
                            <p>Subtotal: $<?php echo number_format($item['subtotal'], 2); ?></p>
                        </div>
                        <div class="carrito-botones">
                            <form action="agregar_compras.php" method="post">
                                <input type="hidden" name="item_id" value="<?php echo $item['item_id']; ?>">
                                <button type="submit" class="carrito-boton-compra">Comprar Ahora</button>
                            </form>

                            <form action="delete-carrito.php" method="post">
                                <input type="hidden" name="item_id" value="<?php echo $item['item_id']; ?>">
                                <input type="hidden" name="producto_id" value="<?php echo $item['producto_id']; ?>">
                                <input type="hidden" name="cantidad" value="<?php echo $item['cantidad']; ?>">
                                <input type="hidden" name="talla" value="<?php echo $item['talla']; ?>">
                                <button type="submit" class="carrito-boton-compra">Quitar del Carrito</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="carrito-total">
                <h3>Total: $<?php echo number_format($total, 2); ?></h3>
                <div class="carrito-total__enlaces">
                    <form action="agregar_compras.php" method="post">
                        <button type="submit" class="carrito-boton-compra">Proceder al Pago</button>
                    </form>
                    <a href="index.php" class="carrito-boton-compra">Seguir Comprando</a>
                </div>
            </div>
        <?php endif; ?>
    </main>


    <footer class="footer">
        <p class="footer__texto">FrontEnd Store - Todos los derechos reservados 2024.</p>
    </footer>
</body>
</html>
