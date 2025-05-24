<?php
session_start();
require_once("connectDB.php");

$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 12;
$offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
$userid = 0;

if (isset($_SESSION['userid'])) {
    $userid = $_SESSION['userid'];
}

$filter = $_GET['filter'] ?? 'recentes';

switch ($filter) {
    case 'populares':
        $orderby = "rb.qtde_likes DESC, rb.data_publicacao DESC, rb.hora_publicacao DESC";
        break;
    case 'recentes':
    default:
        $orderby = "rb.data_publicacao DESC, rb.hora_publicacao DESC";
        break;
}

$query = "
    SELECT 
        rb.*,
        u.nome,
        u.sobrenome,
        ct.nome_popular,
        ct.classe,
        ct.ordem,
        ct.familia,
        ct.especie,
        g.nome_lugar,
        DATE_FORMAT(rb.data_observacao, '%d/%m/%Y') AS data_observacao,
        rb.hora_observacao,
        DATE_FORMAT(rb.data_publicacao, '%d/%m/%Y') AS data_publicacao,
        rb.hora_publicacao,
        (
            SELECT COUNT(*) 
            FROM curtidas_usuarios cu
            WHERE cu.id_usuario = $userid 
              AND cu.id_registro = rb.id
        ) AS liked

    FROM registros_biologicos rb
    JOIN usuarios u ON rb.id_usuario = u.id
    LEFT JOIN classificacao_taxonomica ct ON rb.id_taxonomia = ct.id
    LEFT JOIN geolocalizacao g ON rb.id_geolocalizacao = g.id
    ORDER BY $orderby
    LIMIT $limit OFFSET $offset
";

$result = mysqli_query($conn, $query);

$posts = [];
while ($row = mysqli_fetch_assoc($result)) {
    // Marcar curtida como booleano
    $row['liked'] = $row['liked'] == 1;

    // Substituir valores nulos ou vazios
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
