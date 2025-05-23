<?php
require '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$server = $_ENV['DB_HOST'];
$user = $_ENV['DB_USER'];
$password = $_ENV['DB_PASSWORD'];
$database = $_ENV['DB_NAME'];

$conn = mysqli_connect($server, $user, $password, $database);

if (!$conn) {
    echo "Não conectado ao banco de dados";
}
?>
