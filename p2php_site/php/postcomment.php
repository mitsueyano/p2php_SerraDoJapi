<?php
session_start();
include '../php/connectDB.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    http_response_code(401);
    die(json_encode(['success' => false, 'message' => 'Usuário não autenticado']));
}

//Lê o "corpo" da requisição (esperando um JSON)
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!isset($data['postid']) || !is_numeric($data['postid'])) {
    http_response_code(400);
    die(json_encode(['success' => false, 'message' => 'ID do registro biológico inválido']));
}

if (!isset($data['content']) || empty(trim($data['content']))) {
    http_response_code(400);
    die(json_encode(['success' => false, 'message' => 'Conteúdo do comentário não pode estar vazio']));
}

//Coleta os dados
$postId = $data['postid'];
$content = trim($data['content']);
$parentId = isset($data['parentid']) && is_numeric($data['parentid']) ? $data['parentid'] : null;
$userId = $_SESSION['userid'];

$stmt = $conn->prepare("SELECT id FROM registros_biologicos WHERE id = ?");
$stmt->bind_param("i", $postId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(404);
    die(json_encode(['success' => false, 'message' => 'Registro biológico não encontrado']));
}

//Se for uma resposta a outro comentário, valida o comentário pai
if ($parentId !== null) {
    $stmt = $conn->prepare("SELECT id FROM comentarios WHERE id = ? AND id_registro = ?");
    $stmt->bind_param("ii", $parentId, $postId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        http_response_code(404);
        die(json_encode(['success' => false, 'message' => 'Comentário pai não encontrado']));
    }
}
//Insere o novo comentário no banco
$stmt = $conn->prepare("INSERT INTO comentarios (id_registro, id_usuario, id_comentario_pai, conteudo) VALUES (?, ?, ?, ?)");
$stmt->bind_param("iiis", $postId, $userId, $parentId, $content);

if (!$stmt->execute()) {
    http_response_code(500);
    die(json_encode(['success' => false, 'message' => 'Erro ao cadastrar comentário: ' . $stmt->error]));
}

//Atualiza a contagem de comentários no registro biológico
$updateStmt = $conn->prepare("UPDATE registros_biologicos SET qtde_coment = qtde_coment + 1 WHERE id = ?");
$updateStmt->bind_param("i", $postId);
$updateStmt->execute();

echo json_encode([
    'success' => true,
    'message' => 'Comentário postado com sucesso',
    'commentId' => $stmt->insert_id,
    'userData' => [
        'username' => $_SESSION['username'],
        'name' => $_SESSION['user'], 
        'lastname' => $_SESSION['userlastname'],
        'isSpecialist' => ($_SESSION['access'] === 'especialista'), 
        'pfp' => $_SESSION['pfp']
    ]
]);
?>