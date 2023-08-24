<?php

// Establece la conexión con la base de datos
$host = 'localhost';
$db = 'gestion_pedidos';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

// Definir el DSN (Data Source Name)
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// Opciones de la conexión PDO
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // Crear una nueva instancia de PDO para establecer la conexión
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // En caso de error, lanzar una nueva excepción PDOException
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

$queryPedidos = "
    CREATE TABLE IF NOT EXISTS pedidos (
        pedido_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        vendedor_id INT(6) UNSIGNED NOT NULL,
        cliente_id INT(6) UNSIGNED NOT NULL,
        fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        estado ENUM('pendiente', 'enviado', 'entregado') NOT NULL,
        detalles TEXT
    )
";

$queryClientes = "
    CREATE TABLE IF NOT EXISTS clientes (
        cliente_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(255) NOT NULL,
        direccion VARCHAR(255) NOT NULL,
        telefono VARCHAR(15) NOT NULL
    )
";

$queryVendedores = "
    CREATE TABLE IF NOT EXISTS vendedores (
        vendedor_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL
    );
";

try {
    // Ejecutar las consultas de creación de tablas
    $pdo->exec($queryPedidos);
    $pdo->exec($queryClientes);
    $pdo->exec($queryVendedores);
} catch (PDOException $e) {
    die("Error al crear las tablas: " . $e->getMessage());
}
?>