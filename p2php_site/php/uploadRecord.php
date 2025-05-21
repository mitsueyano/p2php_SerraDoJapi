<?php
include '../php/connectDB.php';
require '../vendor/autoload.php';

session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] != TRUE) {
    header("Location: ../login/login.php");
    exit();
}

use Dotenv\Dotenv;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

Configuration::instance([
    'cloud' => [
        'cloud_name' => $_ENV['CLOUDINARY_CLOUD_NAME'], 
        'api_key' => $_ENV['CLOUDINARY_API_KEY'],
        'api_secret' => $_ENV['CLOUDINARY_API_SECRET'],
    ],
    'url' => ['secure' => true]
]);

if (isset($_FILES['image'])) {

    $common_name = $_POST['common-name'] ?? '';
    $class = $_POST['class'] ?? '';
    $order = $_POST['order'] ?? '';
    $family = $_POST['family'] ?? '';
    $specie = $_POST['scientific-name'] ?? '';
    $date = $_POST['date'] ?? null;
    $time = $_POST['time'] ?? null;
    $comment = $_POST['comment'] ?? '';
    $latitude = $_POST['latitude'] ?? null;
    $longitude = $_POST['longitude'] ?? null;
    $place_name = $_POST['placename'] ?? '';
    $user_id = $_SESSION["userid"] ?? null;

    date_default_timezone_set('America/Sao_Paulo');
    $publication_date = date("Y-m-d");
    $publication_time = date("H:i:s");

    if (!$user_id || !$date || !$time || !$latitude || !$longitude || !$class || !$order || !$family || !$specie || !$place_name) {
        die("Dados obrigatórios ausentes.");
    }

    $tmpFile = $_FILES['image']['tmp_name'];
    $result = (new UploadApi())->upload($tmpFile, [
        'folder' => $_ENV['CLOUDINARY_FOLDER_RECORDS'],
    ]);
    $img_url = $result['secure_url'];


    $stmt = $conn->prepare("INSERT INTO classificacao_taxonomica (classe, ordem, familia, especie) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $class, $order, $family, $specie);
    $stmt->execute();
    $taxon_id = $stmt->insert_id;
    $stmt->close();

    $stmt = $conn->prepare("SELECT id FROM geolocalizacao WHERE latitude = ? AND longitude = ? AND nome_lugar = ?");
    $stmt->bind_param("dds", $latitude, $longitude, $place_name);
    $stmt->execute();
    $stmt->bind_result($geo_id);

    if ($stmt->fetch()) {
        $stmt->close();
    } else {
        $stmt->close();
        $stmt = $conn->prepare("INSERT INTO geolocalizacao (latitude, longitude, nome_lugar) VALUES (?, ?, ?)");
        $stmt->bind_param("dds", $latitude, $longitude, $place_name);
        $stmt->execute();
        $geo_id = $stmt->insert_id;
        $stmt->close();
    }

    $stmt = $conn->prepare("
        INSERT INTO registros_biologicos 
        (id_usuario, nome_popular, id_taxonomia, data_observacao, hora_observacao, descricao, id_geolocalizacao, url_imagem, data_publicacao, hora_publicacao) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param(
        "isisssisss",
        $user_id,
        $common_name,
        $taxon_id,
        $date,
        $time,
        $comment,
        $geo_id,
        $img_url,
        $publication_date,
        $publication_time
    );

    if ($stmt->execute()) {
        header("Location: ../views/explore/explore.php");
    } else {
        echo "Erro ao salvar: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>