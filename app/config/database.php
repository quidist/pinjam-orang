<?php 

$env = parse_ini_file(__DIR__ . '/../../.env');

$dbhost = $env['DB_HOST'];
$dbname = $env['DB_NAME'];
$dbusername = $env['DB_USERNAME'];
$dbpassword = $env['DB_PASSWORD'];
 
try {
    $pdo = new PDO(
         "mysql:host=$dbhost;dbname=$dbname;charset=utf8mb4",
         $dbusername,
         $dbpassword
    );

    $pdo->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

    // Database berhasil terhubung
}catch (PDOException $e){
    die("Koneksi gagal: " . $e->getMessage());
}