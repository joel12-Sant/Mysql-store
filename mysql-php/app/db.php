<?php
$host = 'mysql'; // nombre del servicio en docker-compose
$dbname = 'tienda';
$username = 'root';
$password = 'root';

try {
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // lanza excepciones en errores
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // devuelve arrays asociativos
        PDO::ATTR_EMULATE_PREPARES => false, // usa sentencias preparadas reales
    ];

    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
