<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('connectDB.php');

$specie = isset($_GET['specie']) ? $_GET['specie'] : '';

$data = [];
if ($specie !== '') {
    $stmt = $conn->prepare("SELECT CONCAT(u.nome, ' ', u.sobrenome) AS usuario, r.data_observacao, r.hora_observacao, g.nome_lugar, r.url_imagem
                               FROM registros_biologicos r
                               JOIN usuarios u ON r.id_usuario = u.id
                               JOIN classificacao_taxonomica c ON r.id_taxonomia = c.id
                               JOIN geolocalizacao g ON r.id_geolocalizacao = g.id
                               WHERE c.especie = ?");
    if (!$stmt) {
        echo json_encode(['error' => $conn->error]);
        exit;
    }
    $stmt->bind_param("s", $specie);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result) {
        echo json_encode(['error' => $conn->error]);
        exit;
    }

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($data);
