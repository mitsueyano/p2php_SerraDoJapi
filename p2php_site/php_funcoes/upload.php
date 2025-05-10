<?php
include '../php_funcoes/conectaBD.php';
require '../vendor/autoload.php';

session_start();
if (!isset($_SESSION["logado"]) || $_SESSION["logado"] != TRUE) {
    header("Location: ../login/login.php");
    exit();
}

use Dotenv\Dotenv;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

// Carrega .env
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Configuração Cloudinary
Configuration::instance([
    'cloud' => [
        'cloud_name' => $_ENV['CLOUDINARY_CLOUD_NAME'],
        'api_key'    => $_ENV['CLOUDINARY_API_KEY'],
        'api_secret' => $_ENV['CLOUDINARY_API_SECRET'],
    ],
    'url' => ['secure' => true]
]);

if (isset($_FILES['imagem'])) {
    $arquivoTmp = $_FILES['imagem']['tmp_name'];
    $resultado = (new UploadApi())->upload($arquivoTmp);
    $urlImagem = $resultado['secure_url'];

    // Coleta dos dados
    $nome_popular = $_POST['nomepopular'] ?? '';
    $classe = $_POST['classe'] ?? '';
    $familia = $_POST['familia'] ?? '';
    $especie = $_POST['especie'] ?? '';
    $data = $_POST['data'] ?? null;
    $hora = $_POST['hora'] ?? null;
    $comentario = $_POST['comentario'] ?? '';
    $latitude = $_POST['latitude'] ?? null;
    $longitude = $_POST['longitude'] ?? null;
    $id_usuario = $_SESSION["id_usuario"] ?? null;

    if (!$id_usuario || !$data || !$hora || !$latitude || !$longitude || !$classe || !$familia || !$especie) {
        die("Dados obrigatórios ausentes.");
    }

    // Insere taxonomia e obtém ID
    $stmt = $conexao->prepare("INSERT INTO classificacao_taxonomica (classe, familia, especie) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $classe, $familia, $especie);
    $stmt->execute();
    $id_taxonomia = $stmt->insert_id;
    $stmt->close();

    // Insere geolocalização e obtém ID
   // Verifica se já existe essa localização
    $stmt = $conexao->prepare("SELECT id FROM geolocalizacao WHERE latitude = ? AND longitude = ?");
    $stmt->bind_param("dd", $latitude, $longitude);
    $stmt->execute();
    $stmt->bind_result($id_geolocalizacao);

    if ($stmt->fetch()) {
        // Localização já existe, $id_geolocalizacao está preenchido
        $stmt->close();
    } else {
        $stmt->close();
        // Insere nova localização
        $stmt = $conexao->prepare("INSERT INTO geolocalizacao (latitude, longitude) VALUES (?, ?)");
        $stmt->bind_param("dd", $latitude, $longitude);
        $stmt->execute();
        $id_geolocalizacao = $stmt->insert_id;
        $stmt->close();
    }

    // Insere registro biológico
    $stmt = $conexao->prepare("
        INSERT INTO registros_biologicos 
        (id_usuario, nome_popular, id_taxonomia, data_observacao, horario_observacao, descricao, id_geolocalizacao, url_imagem) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param(
        "isisssis",
        $id_usuario,
        $nome_popular,
        $id_taxonomia,
        $data,
        $hora,
        $comentario,
        $id_geolocalizacao,
        $urlImagem
    );

    if ($stmt->execute()) {
        echo "Imagem e dados enviados com sucesso!<br>";
        echo "<img src='$urlImagem' width='200'><br>";
        echo "URL: $urlImagem";
    } else {
        echo "Erro ao salvar: " . $stmt->error;
    }

    $stmt->close();
    $conexao->close();
}
?>
