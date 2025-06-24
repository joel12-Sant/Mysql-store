<?php require 'db.php'; ?>
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
    <link rel="stylesheet" href="css/styles.css" />
</head>
<body>
    <header class="header">
        <a href="index.php">
            <img class="header__logo" src="img/logo.png" alt="Logotipo" />
        </a>
    </header>

    <nav class="navegacion">
        <a class="navegacion__enlace navegacion__enlace--activo" href="index.php">Tienda</a>
        <a class="navegacion__enlace" href="nosotros.php">Nosotros</a>
    </nav>

    <main>
        <h1>Nuestros Productos</h1>
        <div class="grid">
            <?php
                $playeras = $coleccion->find();
                foreach ($playeras as $p):
            ?>
                <div class="producto">
                    <a href="producto.php?id=<?= htmlspecialchars($p['_id']) ?>">
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
    </main>

    <footer class="footer">
        <p class="footer__texto">FrontEnd Store - Todos los derechos reservado 2024.</p>
    </footer>
</body>
</html>
