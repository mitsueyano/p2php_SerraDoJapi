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
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $latitude = $_POST['latitude'] ?? null;
    $longitude = $_POST['longitude'] ?? null;
    $place_name = $_POST['placename'] ?? '';
    $user_id = $_SESSION["userid"] ?? null;
    $identified = isset($_POST["identified"]) ? 0 : 1;
    $category_name = $_POST["category"] ?? 'Não identificado';
    $valid_types = ['animal', 'ambiental'];
    $incident_type = in_array($_POST['incident_type'], $valid_types) ? $_POST['incident_type'] : 'ambiental';
    $graphic = 0;
    $visible = 0;

    date_default_timezone_set('America/Sao_Paulo');
    $publication_date = date("Y-m-d");
    $publication_time = date("H:i:s");

    if (!$user_id || !$date || !$time || !$latitude || !$longitude || !$place_name) {
        die("Dados obrigatórios ausentes.");
    } else {

        //Query para o tipo da ocorrencia
        $stmt = $conn->prepare("SELECT id FROM tipo_ocorrencia WHERE tipo = ?");
        $stmt->bind_param("s", $incident_type);
        $stmt->execute();
        $stmt->bind_result($incident_type_id);
        if (!$stmt->fetch()) {
            $stmt->close();
            die("Tipo de ocorrência inválido.");
        }
        $stmt->close();

        //Upload da imagem no Cloudinary
        $tmpFile = $_FILES['image']['tmp_name'];
        $result = (new UploadApi())->upload($tmpFile, [
            'folder' => $_ENV['CLOUDINARY_FOLDER_INCIDENTS'],
        ]);
        $img_url = $result['secure_url'];

        if ($identified === true) { //Se o organismo é identificado
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
        INSERT INTO ocorrencias
        (id_usuario, id_geolocalizacao, data_publicacao, hora_publicacao, img_url_ocorrencia, titulo_ocorrencia, descricao_ocorrencia, sensivel, id_taxonomia, id_tipo_ocorrencia, exibicao) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
        $stmt->bind_param(
            "iisssssiiii",
            $user_id,
            $geo_id,
            $publication_date,
            $publication_time,
            $img_url,
            $title,
            $description,
            $graphic,
            $taxon_id,
            $incident_type_id,
            $visible
        );
        if ($stmt->execute()) {
            header("Location: ../views/incidents/incidents.php?$incident_type");
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