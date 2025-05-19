<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('conectaBD.php');

$especie = isset($_GET['especie']) ? $_GET['especie'] : '';

$dados = [];
if ($especie !== '') {
    $stmt = $conexao->prepare("SELECT CONCAT(u.nome, ' ', u.sobrenome) AS usuario, r.data_observacao, r.hora_observacao, g.nome_lugar, r.url_imagem
                               FROM registros_biologicos r
                               JOIN usuarios u ON r.id_usuario = u.id
                               JOIN classificacao_taxonomica c ON r.id_taxonomia = c.id
                               JOIN geolocalizacao g ON r.id_geolocalizacao = g.id
                               WHERE c.especie = ?");
    if (!$stmt) {
        echo json_encode(['erro' => $conexao->error]);
        exit;
    }
    $stmt->bind_param("s", $especie);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if (!$resultado) {
        echo json_encode(['erro' => $conexao->error]);
        exit;
    }

    while ($row = $resultado->fetch_assoc()) {
        $dados[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($dados);
