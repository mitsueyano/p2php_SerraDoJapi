<?php
//--- Função para cadastrar novo comentário ou resposta no banco de dados ---//
session_start();
include '../php/connectDB.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    http_response_code(401);
    die(json_encode(['success' => false, 'message' => 'Usuário não autenticado']));
}

// Obtém os dados do corpo da requisição (JSON)
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Valida os dados recebidos
if (!isset($data['postid']) || !is_numeric($data['postid'])) {
    http_response_code(400);
    die(json_encode(['success' => false, 'message' => 'ID do registro biológico inválido']));
}

if (!isset($data['content']) || empty(trim($data['content']))) {
    http_response_code(400);
    die(json_encode(['success' => false, 'message' => 'Conteúdo do comentário não pode estar vazio']));
}

$postId = $data['postid'];
$content = trim($data['content']);
$parentId = isset($data['parentid']) && is_numeric($data['parentid']) ? $data['parentid'] : null;
$userId = $_SESSION['userid']; // Usando a variável de sessão correta

// Verifica se o registro biológico existe
$stmt = $conn->prepare("SELECT id FROM registros_biologicos WHERE id = ?");
$stmt->bind_param("i", $postId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(404);
    die(json_encode(['success' => false, 'message' => 'Registro biológico não encontrado']));
}

// Se for uma resposta, verifica se o comentário pai existe
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

// Prepara e executa a inserção do comentário
$stmt = $conn->prepare("INSERT INTO comentarios (id_registro, id_usuario, id_comentario_pai, conteudo) VALUES (?, ?, ?, ?)");
$stmt->bind_param("iiis", $postId, $userId, $parentId, $content);

if (!$stmt->execute()) {
    http_response_code(500);
    die(json_encode(['success' => false, 'message' => 'Erro ao cadastrar comentário: ' . $stmt->error]));
}

// Atualiza a contagem de comentários no registro biológico
$updateStmt = $conn->prepare("UPDATE registros_biologicos SET qtde_coment = qtde_coment + 1 WHERE id = ?");
$updateStmt->bind_param("i", $postId);
$updateStmt->execute();

// Retorna sucesso
echo json_encode([
    'success' => true,
    'message' => 'Comentário postado com sucesso',
    'commentId' => $stmt->insert_id,
    'userData' => [
        'username' => $_SESSION['username'], // Retorna o username do usuário
        'name' => $_SESSION['user'], // Retorna o nome do usuário
        'lastname' => $_SESSION['userlastname'], // Retorna o sobrenome do usuário
        'isSpecialist' => ($_SESSION['access'] === 'especialista'), // Retorna se é especialista
        'pfp' => $_SESSION['pfp']
    ]
]);
?>