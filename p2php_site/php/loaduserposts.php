<?php
session_start();
require_once("connectDB.php");
mysqli_set_charset($conn, "utf8mb4");

//Coleta os dados
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 12;
$lastId = isset($_GET['last_id']) ? intval($_GET['last_id']) : 0;
$targetun = isset($_GET['targetun']) ? trim($_GET['targetun']) : '';
$userid = isset($_SESSION['userid']) ? intval($_SESSION['userid']) : 0;
$filter = $_GET['filter'] ?? 'recentes'; //Filtro ainda não aplicado então está como default "recentes"

if ($targetun === '') {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Nome de usuário inválido']);
    exit;
}

switch ($filter) { //Define a ordenação dos registros conforme filtro ("populares" ou "recentes")
    case 'populares':
        $orderby = "rb.qtde_likes DESC, rb.id DESC";
        break;
    case 'recentes':
    default:
        $orderby = "rb.data_publicacao DESC, rb.hora_publicacao DESC, rb.id DESC";
        break;
}

$lastDataPublicacao = null;
$lastQtdeLikes = null;
if ($lastId > 0) { //Se lastId > 0, busca dados do último registro para paginação baseada em data ou likes
    $stmtLast = $conn->prepare("SELECT data_publicacao, qtde_likes FROM registros_biologicos WHERE id = ?");
    $stmtLast->bind_param("i", $lastId);
    $stmtLast->execute();
    $resultLast = $stmtLast->get_result();
    
    if ($resultLast->num_rows === 0) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Último registro inválido']);
        exit;
    }
    
    $lastPost = $resultLast->fetch_assoc();
    $lastDataPublicacao = $lastPost['data_publicacao'];
    $lastQtdeLikes = $lastPost['qtde_likes'];
}

$whereClauses = ["u.nome_usuario = ?"];
$params = [$targetun];
$types = "s";

if ($lastId > 0) { //Adiciona condição para paginação
    if ($filter === 'recentes') {
        $whereClauses[] = "(rb.data_publicacao < ? OR (rb.data_publicacao = ? AND rb.id < ?))";
        $types .= "ssi";
        array_push($params, $lastDataPublicacao, $lastDataPublicacao, $lastId);
    } else {
        $whereClauses[] = "(rb.qtde_likes < ? OR (rb.qtde_likes = ? AND rb.id < ?))";
        $types .= "iii";
        array_push($params, $lastQtdeLikes, $lastQtdeLikes, $lastId);
    }
}

//Query
$query = "
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
        ) AS liked
    FROM registros_biologicos rb
    JOIN usuarios u ON rb.id_usuario = u.id
    LEFT JOIN classificacao_taxonomica ct ON rb.id_taxonomia = ct.id
    LEFT JOIN geolocalizacao g ON rb.id_geolocalizacao = g.id
    WHERE " . implode(' AND ', $whereClauses) . "
    ORDER BY $orderby
    LIMIT ?
";

$stmt = $conn->prepare($query);
$types = "i" . $types . "i"; 
array_unshift($params, $userid); 
array_push($params, $limit); 

$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

$posts = [];
while ($row = $result->fetch_assoc()) {
    $row['nome_popular'] = trim($row['nome_popular'] ?? '') ?: 'Não identificado';
    $row['especie'] = trim($row['especie'] ?? '') ?: 'Não identificado';
    $posts[] = $row;
    
}

//Verifica se ainda há mais registros além do limite
$hasMore = count($posts) === $limit;
$lastLoadedId = $lastId;
if (!empty($posts)) {
    $lastLoadedId = end($posts)['id'];
}

header('Content-Type: application/json');
echo json_encode([
    'registros' => $posts,
    'last_id' => $lastLoadedId,
    'has_more' => $hasMore
]);

$conn->close();
?>