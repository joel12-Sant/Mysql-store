<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sobre Nosotros - FrontEnd Store</title>
    <link rel="stylesheet" href="css/normalize.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Krub:wght@300;700&family=Staatliches&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="css/styles.css" />
</head>
<body>
    <header class="header">
        <a href="index.php">
            <img class="header__logo" src="img/logo.png" alt="Logotipo" />
        </a>
    </header>

    <nav class="navegacion">
        <a class="navegacion__enlace" href="index.php">Tienda</a>
        <a class="navegacion__enlace navegacion__enlace--activo" href="nosotros.php">Nosotros</a>
    </nav>

    <main class="contenedor">
        <h1>Nosotros</h1>

        <div class="nosotros">
            <div class="nosotros__contenido">
                <p>
                    En <strong>FrontEnd Store</strong>, combinamos nuestra pasión por la programación y el diseño en una tienda única para desarrolladores.
                    Este proyecto nació como una tarea escolar, pero lo hemos convertido en una experiencia real de emprendimiento digital.
                </p>
                <p>
                    Cada prenda está inspirada en herramientas reales que usamos a diario como programadores. No solo son camisetas: son una forma de decirle al mundo “soy dev y me encanta”.
                    Además, nos permite aplicar lo que aprendemos de bases de datos, contenedores, backend y frontend en un solo sistema funcional.
                </p>
            </div>
            <img class="nosotros__imagen" src="img/nosotros.jpg" alt="Imagen nosotros" />
        </div>
    </main>

    <section class="contenedor comprar">
        <h2 class="comprar__titulo">¿Por qué comprar con nosotros?</h2>

        <div class="bloques">
            <div class="bloque">
                <img class="bloque__imagen" src="img/icono_1.png" alt="Icono Mejor precio" />
                <h3 class="bloque__titulo">El mejor precio</h3>
                <p>
                    Precios accesibles sin sacrificar calidad. Ideal para estudiantes y desarrolladores que quieren estilo y código.
                </p>
            </div>

            <div class="bloque">
                <img class="bloque__imagen" src="img/icono_2.png" alt="Icono Devs" />
                <h3 class="bloque__titulo">Para devs</h3>
                <p>
                    Diseños pensados por y para programadores. Si hablas JavaScript, esta es tu tienda.
                </p>
            </div>

            <div class="bloque">
                <img class="bloque__imagen" src="img/icono_3.png" alt="Icono Envío gratis" />
                <h3 class="bloque__titulo">Envío gratis</h3>
                <p>
                    No importa en qué terminal estés, el envío va por nuestra cuenta a todo México.
                </p>
            </div>

            <div class="bloque">
                <img class="bloque__imagen" src="img/icono_4.png" alt="Icono Calidad" />
                <h3 class="bloque__titulo">Mejor calidad</h3>
                <p>
                    Camisetas resistentes, impresiones duraderas y cómodas para programar largas horas.
                </p>
            </div>
        </div>
    </section>

    <footer class="footer">
        <p class="footer__texto">FrontEnd Store - Todos los derechos reservados 2024.</p>
    </footer>
</body>
</html>
