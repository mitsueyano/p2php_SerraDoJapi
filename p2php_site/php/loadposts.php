<?php
session_start();
require_once("connectDB.php");

$limit = isset($_GET['limit']) ? max(1, min(intval($_GET['limit']), 50)) : 12;
$offset = isset($_GET['offset']) ? max(0, intval($_GET['offset'])) : 0;
$userid = isset($_SESSION['userid']) ? intval($_SESSION['userid']) : 0;

$allowed_filters = ['recentes', 'populares'];
$filter = $_GET['filter'] ?? 'recentes';
if (!in_array($filter, $allowed_filters)) {
    $filter = 'recentes';
}

$orderby = $filter === 'populares'
    ? "rb.qtde_likes DESC, rb.data_publicacao DESC, rb.hora_publicacao DESC"
    : "rb.data_publicacao DESC, rb.hora_publicacao DESC";

$sql = "
    SELECT 
        rb.*,
        u.nome,
        u.sobrenome,
        u.nome_usuario,
        ct.nome_popular,
        ct.classe,
        ct.ordem,
        ct.familia,
        ct.especie,
        ct.id_categoria,
        g.nome_lugar,
        DATE_FORMAT(rb.data_observacao, '%d/%m/%Y') AS data_observacao,
        rb.hora_observacao,
        DATE_FORMAT(rb.data_publicacao, '%d/%m/%Y') AS data_publicacao,
        rb.hora_publicacao,
        (
            SELECT COUNT(*) 
            FROM curtidas_usuarios cu
            WHERE cu.id_usuario = ? 
            AND cu.id_registro = rb.id
        ) AS liked,
        (
            SELECT COUNT(*) 
            FROM comentarios c
            WHERE c.id_registro = rb.id
        ) AS qtde_coment
    FROM registros_biologicos rb
    JOIN usuarios u ON rb.id_usuario = u.id
    LEFT JOIN classificacao_taxonomica ct ON rb.id_taxonomia = ct.id
    LEFT JOIN geolocalizacao g ON rb.id_geolocalizacao = g.id
    ORDER BY $orderby
    LIMIT ? OFFSET ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $userid, $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();

$posts = [];
while ($row = $result->fetch_assoc()) {
    $row['liked'] = $row['liked'] == 1;
    $row['nome_popular'] = trim($row['nome_popular'] ?? '') ?: 'Não identificado';
    $row['classe'] = trim($row['classe'] ?? '') ?: ' ';
    $row['ordem'] = trim($row['ordem'] ?? '') ?: ' ';
    $row['familia'] = trim($row['familia'] ?? '') ?: ' ';
    $row['especie'] = trim($row['especie'] ?? '') ?: 'Não identificado';
    $row['nome_lugar'] = trim($row['nome_lugar'] ?? '') ?: ' ';
    $posts[] = $row;
}

header('Content-Type: application/json');
echo json_encode($posts);
