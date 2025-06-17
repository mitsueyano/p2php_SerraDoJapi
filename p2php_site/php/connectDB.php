<?php
require __DIR__ . '/../vendor/autoload.php'; //Carrega as classes PHP das bibliotecas instaladas via Composer

use Dotenv\Dotenv; //Arquivo .env para dados sensíveis (/p2php_SerraDoJapi/p2php_site/.env)
$dotenv = Dotenv::createImmutable(__DIR__ . '/../'); //Carrega as variáveis do arquivo .env, sem permitir que sejam sobrescritas depois.
$dotenv->load();

//Acesso aos dados do .env
$server = $_ENV['DB_HOST'];
$user = $_ENV['DB_USER'];
$password = $_ENV['DB_PASSWORD'];
$database = $_ENV['DB_NAME'];

//Conexão BD
$conn = mysqli_connect($server, $user, $password, $database);
if (!$conn) {
    die("Não conectado ao banco de dados");
}
?>
