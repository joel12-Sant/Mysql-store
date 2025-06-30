<?php
session_start();
require 'db.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

try {
    $usuarioId = (int)$_SESSION['usuario_id'];

    $stmt = $conexion->prepare("SELECT * FROM compras WHERE usuario_id = ? ORDER BY fecha DESC");
    $stmt->execute([$usuarioId]);
    $compras = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $comprasConNombres = [];

    foreach ($compras as $compra) {
        $compraId = $compra['id'];
        
        $stmtItems = $conexion->prepare("
            SELECT ci.*, p.nombre 
            FROM compra_items ci 
            JOIN playeras p ON ci.playera_id = p.id 
            WHERE ci.compra_id = ?
        ");
        $stmtItems->execute([$compraId]);
        $items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

        $compra['productos'] = $items;
        $comprasConNombres[] = $compra;
    }

} catch (Exception $e) {
    $mensaje = "Error al cargar las compras: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Compras - FrontEnd Store</title>
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Krub:wght@300;700&family=Staatliches&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/styles.css?v=<?php echo time(); ?>">
</head>
<body>
    <header class="header">
        <a href="index.php" style="display: block; text-align: center;">
            <img class="header__logo" src="img/logo.png" alt="Logotipo" />
        </a>
        <div class="right-buttons">
            <a href="logout.php" class="auth-button">Cerrar Sesión</a>
        </div>
    </header>

    <nav class="navegacion">
        <?php if (isset($_SESSION['usuario_id'])): ?>
            <a class="navegacion__enlace" href="mi-perfil.php">Mi Perfil</a>
        <?php endif; ?>
        <a class="navegacion__enlace" href="index.php">Tienda</a>
        <a class="navegacion__enlace" href="carrito.php">Carrito</a>
        <a class="navegacion__enlace" href="nosotros.php">Nosotros</a>
    </nav>

    <main class="contenedor">
        <h1>Mis Compras</h1>
        
        <?php if (isset($_SESSION['mensaje_exito'])): ?>
            <div class="mensaje-exito">
                <?php echo htmlspecialchars($_SESSION['mensaje_exito']); ?>
            </div>
            <?php unset($_SESSION['mensaje_exito']); ?>
        <?php endif; ?>
        
        <?php if (isset($mensaje)): ?>
            <p class="carrito-vacio"><?php echo htmlspecialchars($mensaje); ?></p>
        <?php else: ?>
            <?php foreach ($comprasConNombres as $compra): ?>
                <div class="carrito-item" style="grid-template-columns: 1fr 4fr;">
                    <div class="carrito-item-info">
                        <h3>Compra del: <?php echo date('d/m/Y H:i', strtotime($compra['fecha'])); ?></h3>
                        <p><strong>Total:</strong> $<?php echo number_format($compra['total'], 2); ?></p>
                        <p><strong>Productos comprados:</strong></p>
                        <ul>
                            <?php foreach ($compra['productos'] as $producto): ?>
                                <li>
                                    Playera: <?php echo htmlspecialchars($producto['nombre']); ?> |
                                    Talla: <?php echo htmlspecialchars($producto['talla']); ?> |
                                    Cantidad: <?php echo $producto['cantidad']; ?> |
                                    Precio: $<?php echo number_format($producto['precio_unitario'], 2); ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>
    <footer class="footer">
        <p class="footer__texto">FrontEnd Store - Todos los derechos reservado 2024.</p>
    </footer>
</body>
</html>
