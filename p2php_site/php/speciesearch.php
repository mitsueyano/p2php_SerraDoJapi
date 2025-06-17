<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('connectDB.php');

//Coleta os dados
$specie = isset($_GET['specie']) ? $_GET['specie'] : '';

$data = [];
if ($specie !== '') { //Se a espécie foi fornecida
    if ($specie === 'Não identificado') { //Quando a espécie ainda não foi identificada
        //Query para registros que ainda não foram identificados
        $stmt = $conn->prepare("
            SELECT 
                r.id AS id,
                u.id AS id_usuario,
                u.nome_usuario AS usuario,
                r.data_observacao, 
                r.hora_observacao, 
                g.nome_lugar, 
                r.url_imagem,
                '' AS nome_popular
            FROM registros_biologicos r
            JOIN usuarios u ON r.id_usuario = u.id
            JOIN geolocalizacao g ON r.id_geolocalizacao = g.id
            WHERE r.identificacao = FALSE
        ");
    } else { //Caso a espécie seja conhecida, busca registros com a espécie exata
        $stmt = $conn->prepare("
            SELECT 
                r.id AS id,
                u.id AS id_usuario,
                u.nome_usuario AS usuario,
                r.data_observacao, 
                r.hora_observacao, 
                g.nome_lugar, 
                r.url_imagem,
                c.nome_popular
            FROM registros_biologicos r
            JOIN usuarios u ON r.id_usuario = u.id
            JOIN classificacao_taxonomica c ON r.id_taxonomia = c.id
            JOIN geolocalizacao g ON r.id_geolocalizacao = g.id
            WHERE c.especie = ?
        ");
        if ($stmt) {
            $stmt->bind_param("s", $specie);
        }
    }

    if (!$stmt) {
        echo json_encode(['error' => $conn->error]);
        exit;
    }

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
