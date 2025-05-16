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
        'api_key' => $_ENV['CLOUDINARY_API_KEY'],
        'api_secret' => $_ENV['CLOUDINARY_API_SECRET'],
    ],
    'url' => ['secure' => true]
]);

if (isset($_FILES['imagem'])) {

    // Coleta dos dados
    $nome_popular = $_POST['nomepopular'] ?? '';
    $classe = $_POST['classe'] ?? '';
    $ordem = $_POST['ordem'] ?? '';
    $familia = $_POST['familia'] ?? '';
    $especie = $_POST['nomecientifico'] ?? '';
    $data = $_POST['data'] ?? null;
    $hora = $_POST['hora'] ?? null;
    $comentario = $_POST['comentario'] ?? '';
    $latitude = $_POST['latitude'] ?? null;
    $longitude = $_POST['longitude'] ?? null;
    $nome_lugar = $_POST['nomelugar'] ?? ''; // <-- NOVO
    $id_usuario = $_SESSION["id_usuario"] ?? null;

    // Data e hora da publicação (agora)
    date_default_timezone_set('America/Sao_Paulo');
    $data_publicacao = date("Y-m-d");
    $hora_publicacao = date("H:i:s");

    // Validação obrigatória
    if (!$id_usuario || !$data || !$hora || !$latitude || !$longitude || !$classe || !$ordem || !$familia || !$especie || !$nome_lugar) {
        die("Dados obrigatórios ausentes.");
    }

    // Upload da imagem
    $arquivoTmp = $_FILES['imagem']['tmp_name'];
    $resultado = (new UploadApi())->upload($arquivoTmp);
    $urlImagem = $resultado['secure_url'];

    // Insere taxonomia e obtém ID
    $stmt = $conexao->prepare("INSERT INTO classificacao_taxonomica (classe, ordem, familia, especie) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $classe, $ordem, $familia, $especie);
    $stmt->execute();
    $id_taxonomia = $stmt->insert_id;
    $stmt->close();

    // Verifica se já existe essa localização
    $stmt = $conexao->prepare("SELECT id FROM geolocalizacao WHERE latitude = ? AND longitude = ? AND nome_lugar = ?");
    $stmt->bind_param("dds", $latitude, $longitude, $nome_lugar);
    $stmt->execute();
    $stmt->bind_result($id_geolocalizacao);

    if ($stmt->fetch()) {
        $stmt->close();
    } else {
        $stmt->close();
        $stmt = $conexao->prepare("INSERT INTO geolocalizacao (latitude, longitude, nome_lugar) VALUES (?, ?, ?)");
        $stmt->bind_param("dds", $latitude, $longitude, $nome_lugar);
        $stmt->execute();
        $id_geolocalizacao = $stmt->insert_id;
        $stmt->close();
    }

    // Insere registro biológico
    $stmt = $conexao->prepare("
        INSERT INTO registros_biologicos 
        (id_usuario, nome_popular, id_taxonomia, data_observacao, hora_observacao, descricao, id_geolocalizacao, url_imagem, data_publicacao, hora_publicacao) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param(
        "isisssisss",
        $id_usuario,
        $nome_popular,
        $id_taxonomia,
        $data,
        $hora,
        $comentario,
        $id_geolocalizacao,
        $urlImagem,
        $data_publicacao,
        $hora_publicacao
    );

    if ($stmt->execute()) {
        header("Location: ../telas/explorar/explorar.php");
    } else {
        echo "Erro ao salvar: " . $stmt->error;
    }

    $stmt->close();
    $conexao->close();
}
?>