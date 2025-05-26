<?php
session_start();
include("connectDB.php");
mysqli_set_charset($conn, "utf8mb4");

// Configuração de timeout
set_time_limit(30); // Limite de 30 segundos
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Parâmetros
$targetun = filter_input(INPUT_GET, 'targetun', FILTER_SANITIZE_STRING);
$userid = $_SESSION['userid'] ?? 0;
$limit = filter_input(INPUT_GET, 'limit', FILTER_VALIDATE_INT, ['options' => ['default' => 12, 'min_range' => 1]]);
$lastId = filter_input(INPUT_GET, 'last_id', FILTER_VALIDATE_INT, ['options' => ['default' => 0, 'min_range' => 0]]);

// Verificação rápida se usuário existe
$stmt_check = $conn->prepare("SELECT id FROM usuarios WHERE nome_usuario = ? LIMIT 1");
$stmt_check->bind_param("s", $targetun);
$stmt_check->execute();

if ($stmt_check->get_result()->num_rows === 0) {
    http_response_code(404);
    echo json_encode(["error" => "Usuário não encontrado"]);
    exit;
}

// Verificação simplificada se há curtidas
$stmt_count = $conn->prepare("
    SELECT COUNT(*) as total 
    FROM curtidas_usuarios cu
    JOIN usuarios u ON cu.id_usuario = u.id
    WHERE u.nome_usuario = ?
");
$stmt_count->bind_param("s", $targetun);
$stmt_count->execute();

if ($stmt_count->get_result()->fetch_assoc()['total'] === 0) {
    echo json_encode([
        'registros' => [],
        'last_id' => 0,
        'has_more' => false
    ]);
    exit;
}

// Paginação otimizada
try {
    $whereClause = "u_target.nome_usuario = ?";
    $params = [$targetun];
    $types = "s";
    
    if ($lastId > 0) {
        $whereClause .= " AND rb.id < ?";
        $types .= "i";
        $params[] = $lastId;
    }

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
        g.nome_lugar,
        DATE_FORMAT(rb.data_observacao, '%d/%m/%Y') AS data_observacao,
        rb.hora_observacao,
        DATE_FORMAT(rb.data_publicacao, '%d/%m/%Y') AS data_publicacao,
        rb.hora_publicacao,
        EXISTS (
            SELECT 1 FROM curtidas_usuarios 
            WHERE id_usuario = ? AND id_registro = rb.id
        ) as liked
    FROM curtidas_usuarios cu
    INNER JOIN usuarios u_target ON cu.id_usuario = u_target.id
    INNER JOIN registros_biologicos rb ON cu.id_registro = rb.id
    INNER JOIN usuarios u ON rb.id_usuario = u.id
    LEFT JOIN classificacao_taxonomica ct ON rb.id_taxonomia = ct.id
    LEFT JOIN geolocalizacao g ON rb.id_geolocalizacao = g.id
    WHERE $whereClause
    ORDER BY rb.id DESC
    LIMIT ?
";

    $stmt = $conn->prepare($sql);
    $types = "i" . $types . "i"; // userid + tipos anteriores + limit
    array_unshift($params, $userid);
    $params[] = $limit + 1; // Verifica se tem mais registros
    
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    $posts = [];
    $hasMore = false;
    
    while ($row = $result->fetch_assoc()) {
        if (count($posts) < $limit) {
            
            $row['nome_popular'] = trim($row['nome_popular'] ?? '') ?: 'Não identificado';
            $row['especie'] = trim($row['especie'] ?? '') ?: 'Não identificado';
            $posts[] = $row;
        } else {
            $hasMore = true;
        }
    }

    echo json_encode([
        'registros' => $posts,
        'last_id' => $posts ? end($posts)['id'] : 0,
        'has_more' => $hasMore
    ]);

} catch (mysqli_sql_exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Erro no servidor: " . $e->getMessage()]);
}
?>