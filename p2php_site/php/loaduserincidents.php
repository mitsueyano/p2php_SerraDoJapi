<?php
include("connectDB.php");
mysqli_set_charset($conn, "utf8mb4");

$targetun = isset($_GET['targetun']) ? trim($_GET['targetun']) : '';
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
$lastId = isset($_GET['last_id']) ? intval($_GET['last_id']) : 0;
$lastDate = isset($_GET['last_date']) ? $_GET['last_date'] : null;

if ($targetun === '') {
    http_response_code(400);
    echo json_encode(["error" => "Parâmetro 'targetun' é obrigatório."]);
    exit;
}

if ($lastId > 0) {
    $stmtLast = $conn->prepare("
        SELECT data_publicacao 
        FROM ocorrencias 
        WHERE id = ? AND exibicao = TRUE
    ");
    $stmtLast->bind_param("i", $lastId);
    $stmtLast->execute();
    $resultLast = $stmtLast->get_result();
    
    if ($resultLast->num_rows === 0) {
        http_response_code(400);
        echo json_encode(['error' => 'Último registro inválido']);
        exit;
    }
    
    $lastPost = $resultLast->fetch_assoc();
    $lastDate = $lastPost['data_publicacao'];
}

$whereClause = "u.nome_usuario = ? AND n.exibicao = TRUE";
$params = [$targetun];
$types = "s";

if ($lastId > 0) {
    $whereClause .= " AND (n.data_publicacao < ? OR (n.data_publicacao = ? AND n.id < ?))";
    $types .= "ssi";
    array_push($params, $lastDate, $lastDate, $lastId);
}

$sql = "
    SELECT 
        n.id,
        n.titulo_ocorrencia, 
        n.descricao_ocorrencia, 
        u.nome AS autor, 
        n.sensivel, 
        n.img_url_ocorrencia, 
        g.nome_lugar, 
        n.data_publicacao, 
        n.hora_publicacao
    FROM ocorrencias n
    INNER JOIN usuarios u ON n.id_usuario = u.id
    INNER JOIN geolocalizacao g ON n.id_geolocalizacao = g.id
    WHERE $whereClause
    ORDER BY n.data_publicacao DESC, n.hora_publicacao DESC, n.id DESC
    LIMIT ?
";

array_push($params, $limit + 1);
$types .= "i";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

$incidents = [];
$hasMore = false;
$count = 0;

while ($row = $result->fetch_assoc()) {
    if (++$count <= $limit) {
        $incidents[] = [
            'id' => $row['id'],
            'titulo' => $row['titulo_ocorrencia'],
            'descricao' => $row['descricao_ocorrencia'],
            'autor' => $row['autor'],
            'nome_lugar' => $row['nome_lugar'],
            'sensivel' => (bool)$row['sensivel'],
            'img_url' => $row['img_url_ocorrencia'],
            'data' => $row['data_publicacao'],
            'hora' => $row['hora_publicacao'],
        ];
    } else {
        $hasMore = true;
    }
}

$lastLoadedId = $lastId;
if (!empty($incidents)) {
    $lastLoadedId = end($incidents)['id'];
}

header('Content-Type: application/json');
echo json_encode([
    'registros' => $incidents,
    'last_id' => $lastLoadedId,
    'has_more' => $hasMore
]);
?>