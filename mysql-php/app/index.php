<?php require 'db.php'; 
session_start(); 

$stmt = $conexion->prepare("SELECT * FROM playeras");
$stmt->execute();
$playeras = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>FrontEnd Store</title>
    <link rel="stylesheet" href="css/normalize.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Krub:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;1,200;1,300;1,400;1,500;1,600;1,700&family=Staatliches&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="/css/styles.css?v=<?php echo time(); ?>">
    <script src="js/index.js"> </script>
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
        <a class="navegacion__enlace navegacion__enlace--activo" href="index.php">Tienda</a>
        <a class="navegacion__enlace" href="carrito.php">Carrito</a>
        <a class="navegacion__enlace" href="nosotros.php">Nosotros</a>
    </nav>

    <main>
        <div class="contenedor">
            <h1>Nuestros Productos</h1>
            <div class="search-container">
                <input type="text" id="searchInput" class="search-input" placeholder="Buscar productos..." autocomplete="off">
            </div>
        </div>
        
        <div class="grid" id="productGrid">
            <?php
                foreach ($playeras as $p):
            ?>
                <div class="producto" data-name="<?= strtolower(htmlspecialchars($p['nombre'])) ?>">
                    <a href="producto.php?id=<?= htmlspecialchars($p['id']) ?>">
                        <img class="producto__imagen" src="img/<?= htmlspecialchars($p['imagen']) ?>" alt="Imagen <?= htmlspecialchars($p['nombre']) ?>" />
                        <div class="producto__informacion">
                            <p class="producto__nombre"><?= htmlspecialchars($p['nombre']) ?></p>
                            <p class="producto__precio">$<?= htmlspecialchars($p['precio']) ?></p>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
            <div class="grafico grafico--camisas"></div>
            <div class="grafico grafico--node"></div>
        </div>
        
        <div id="noResults" class="no-results" style="display: none;">
            No se encontraron productos que coincidan con tu búsqueda.
        </div>
    </main>

    <footer class="footer">
        <p class="footer__texto">FrontEnd Store - Todos los derechos reservado 2024.</p>
    </footer>
</body>
</html>