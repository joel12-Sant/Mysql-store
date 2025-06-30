<?php
session_start();
require 'db.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

try {
    $usuarioId = (int)$_SESSION['usuario_id'];

    $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt->execute([$usuarioId]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        throw new Exception("Usuario no encontrado.");
    }
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil</title>
    <link rel="stylesheet" href="css/normalize.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Krub:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;1,200;1,300;1,400;1,500;1,600;1,700&family=Staatliches&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="/css/styles.css?v=<?php echo time(); ?>">
    <script>
        function confirmarEliminacion() {
            if (confirm('¿Estás seguro de que deseas eliminar tu cuenta? Esta acción no se puede deshacer.')) {
                document.getElementById('formEliminarCuenta').submit();
            }
        }
    </script>
</head>
<body>
    <header class="header">
        <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
            <div class="left-buttons">
                <a href='find.php' class="admin-button">Administrar Productos</a>
            </div>
        <?php endif; ?>
        
        <a href="index.php" style="display: block; text-align: center;">
            <img class="header__logo" src="img/logo.png" alt="Logotipo" />
        </a>
        
        <div class="right-buttons">
            <a href="logout.php" class="auth-button">Cerrar Sesión</a>
        </div>
    </header>

    <nav class="navegacion">
        <a class="navegacion__enlace navegacion__enlace--activo" href="mi-perfil.php">Mi Perfil</a>
        <a class="navegacion__enlace" href="index.php">Tienda</a>
        <a class="navegacion__enlace" href="carrito.php">Carrito</a>
        <a class="navegacion__enlace" href="nosotros.php">Nosotros</a>
    </nav>

    <main>
        <div class="profile-container">
            <div class="profile-header">
                <h1>Mi Perfil</h1>
                <p>Rol: <?= htmlspecialchars(ucfirst($usuario['rol'])) ?></p>
            </div>
            
            <div class="profile-info">
                <div class="info-row">
                    <div class="info-label">Nombre:</div>
                    <div class="info-value"><?= htmlspecialchars($usuario['nombre']) ?></div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Correo:</div>
                    <div class="info-value"><?= htmlspecialchars($usuario['correo']) ?></div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Dirección:</div>
                    <div class="info-value"><?= htmlspecialchars($usuario['direccion']) ?></div>
                </div>
            </div>
            
            <div class="member-since">
                Miembro desde: <?= date('d/m/Y', strtotime($usuario['creado_en'])) ?>
            </div>
            
            <div class="button-group">
                <a href="compras.php" class="carrito-boton-compra">Mis Compras</a>
                <button onclick="confirmarEliminacion()" class="carrito-boton-compra">Eliminar Cuenta</button>
            </div>
        </div>
    </main>

    <footer class="footer">
        <p class="footer__texto">FrontEnd Store - Todos los derechos reservado 2024.</p>
    </footer>
    
    <form id="formEliminarCuenta" action="delete-cuenta.php" method="post" style="display: none;"></form>
</body>
</html>
