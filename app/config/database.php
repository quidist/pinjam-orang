<?php 
/**
 * PINJAMORANG - Database Connection (PDO)
 * File: app/config/database.php
 */

$envFile = __DIR__ . '/../../.env';
$env = file_exists($envFile) ? parse_ini_file($envFile) : [];

$dbhost     = !empty($env['DB_HOST']) ? $env['DB_HOST'] : '127.0.0.1';
$dbport     = !empty($env['DB_PORT']) ? $env['DB_PORT'] : '3306';
$dbname     = !empty($env['DB_NAME']) ? $env['DB_NAME'] : 'pinjamorang';
$dbusername = !empty($env['DB_USERNAME']) ? $env['DB_USERNAME'] : 'root';
$dbpassword = isset($env['DB_PASSWORD']) ? $env['DB_PASSWORD'] : '';
 
try {
    $pdo = new PDO(
        "mysql:host={$dbhost};port={$dbport};dbname={$dbname};charset=utf8mb4",
        $dbusername,
        $dbpassword
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Database berhasil terhubung
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
