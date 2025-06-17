<?php
include '../php/connectDB.php';
require '../vendor/autoload.php';
use Dotenv\Dotenv;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

//Verificação de sessão
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] != TRUE) {
    header("Location: ../login/login.php");
    exit();
}

//Configuração de acesso ao Cloudinary
Configuration::instance([
    'cloud' => [
        'cloud_name' => $_ENV['CLOUDINARY_CLOUD_NAME'],
        'api_key' => $_ENV['CLOUDINARY_API_KEY'],
        'api_secret' => $_ENV['CLOUDINARY_API_SECRET'],
    ],
    'url' => ['secure' => true]
]);

//Coleta os dados
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $common_name = $_POST['common-name'] ?? 'Não identificado';
    $class = $_POST['class'] ?? null;
    $order = $_POST['order'] ?? null;
    $family = $_POST['family'] ?? null;
    $specie = $_POST['scientific-name'] ?? null;
    $date = $_POST['date'] ?? null;
    $time = $_POST['time'] ?? null;
    $comment = $_POST['comment'] ?? '';
    $latitude = $_POST['latitude'] ?? null;
    $longitude = $_POST['longitude'] ?? null;
    $place_name = $_POST['placename'] ?? '';
    $user_id = $_SESSION["userid"] ?? null;
    $identified = isset($_POST["identified"]) ? 0 : 1;
    $is_invader = isset($_POST["invader"]) ? 1 : 0;
    $category_name = $is_invader ? 'Espécie invasora' : ($_POST["category"] ?? 'não identificado');

    date_default_timezone_set('America/Sao_Paulo');
    $publication_date = date("Y-m-d");
    $publication_time = date("H:i:s");

    if (!$user_id || !$date || !$time || !$latitude || !$longitude || !$place_name) {
        die("Dados obrigatórios ausentes.");
    } else {
        //Upload da imagem no Cloudinary
        $tmpFile = $_FILES['image']['tmp_name'];
        $result = (new UploadApi())->upload($tmpFile, [
            'folder' => $_ENV['CLOUDINARY_FOLDER_RECORDS'],
        ]);
        $img_url = $result['secure_url'];

        if ($identified == true) { //Se o ser organismo é identificado
            $stmt = $conn->prepare("SELECT id FROM categoria WHERE nome = ?");
            $stmt->bind_param("s", $category_name);
            $stmt->execute();
            $stmt->bind_result($category_id);
            if (!$stmt->fetch()) {
                die("Categoria inválida.");
            }
            $stmt->close();

            $stmt = $conn->prepare("SELECT id FROM classificacao_taxonomica WHERE nome_popular = ? AND classe = ? AND ordem = ? AND familia = ? AND especie = ? AND id_categoria = ?");
            $stmt->bind_param("sssssi", $common_name, $class, $order, $family, $specie, $category_id);
            $stmt->execute();
            $stmt->bind_result($taxon_id);
            if ($stmt->fetch()) {
                $stmt->close();
            } else {
                $stmt->close();
                $stmt = $conn->prepare("INSERT INTO classificacao_taxonomica (nome_popular, classe, ordem, familia, especie, id_categoria) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssssi", $common_name, $class, $order, $family, $specie, $category_id);
                $stmt->execute();
                $taxon_id = $stmt->insert_id;
                $stmt->close();
            }
        } else { //Se o organismo não é identificado
            $taxon_id = NULL;
            $category = NULL;
        }

        //Query para a geolocalização
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

        //Query final
        $stmt = $conn->prepare("
    INSERT INTO registros_biologicos 
    (id_usuario, id_taxonomia, data_observacao, hora_observacao, descricao, id_geolocalizacao, url_imagem, qtde_likes, qtde_coment, data_publicacao, hora_publicacao, identificacao) 
    VALUES (?, ?, ?, ?, ?, ?, ?, 0, 0, ?, ?, ?)
");
        $stmt->bind_param(
            "iisssissss",
            $user_id,
            $taxon_id,
            $date,
            $time,
            $comment,
            $geo_id,
            $img_url,
            $publication_date,
            $publication_time,
            $identified,
        );

        if ($stmt->execute()) {
            header("Location: ../views/explore/explore.php");
        } else {
            echo "Erro ao salvar: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    }
} else {
    echo "Método inválido.";
}
?>