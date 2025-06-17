<?php
session_start();
require_once("connectDB.php");
mysqli_set_charset($conn, "utf8mb4");

//Verifica se o método HTTP da requisição é DELETE
if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    header('Content-Type: application/json'); //Define o retorno como JSON
    http_response_code(405);
    echo json_encode(['error' => 'Método não permitido. Use DELETE.']);
    exit;
}

//Verifica se o usuário está autenticado
if (!isset($_SESSION['userid'])) {
    header('Content-Type: application/json');
    http_response_code(401);
    echo json_encode(['error' => 'Usuário não autenticado']);
    exit;
}

//Extrai o ID do post a partir da URL
$urlParts = explode('/', $_SERVER['REQUEST_URI']);
$postId = (int) end($urlParts);

//Verifica se o ID extraído é válido
if ($postId <= 0) {
    header('Content-Type: application/json');
    http_response_code(400);
    echo json_encode(['error' => 'ID do post inválido']);
    exit;
}

//Query para verificar se o post existe e pegar o id do dono
$stmtCheck = $conn->prepare("SELECT id_usuario FROM registros_biologicos WHERE id = ?");
$stmtCheck->bind_param("i", $postId); // Liga o parâmetro (int)
$stmtCheck->execute();
$resultCheck = $stmtCheck->get_result();
//Verifica se o post foi encontrado
if ($resultCheck->num_rows === 0) {
    header('Content-Type: application/json');
    http_response_code(404);
    echo json_encode(['error' => 'Post não encontrado']);
    exit;
}

//Pega o id do usuário dono do post
$postOwner = $resultCheck->fetch_assoc();
// Verifica se o usuário logado é o dono do post
if ($postOwner['id_usuario'] != $_SESSION['userid']) {
    header('Content-Type: application/json');
    http_response_code(403);
    echo json_encode(['error' => 'Você não tem permissão para excluir este post']);
    exit;
}

//Query para exclusão do post
$stmtDelete = $conn->prepare("DELETE FROM registros_biologicos WHERE id = ?");
$stmtDelete->bind_param("i", $postId);
if ($stmtDelete->execute()) {
    if ($stmtDelete->affected_rows > 0) {
        header('Content-Type: application/json');
        echo json_encode(['success' => 'Post excluído com sucesso']);
    } else {
        header('Content-Type: application/json');
        http_response_code(500);
        echo json_encode(['error' => 'Nenhum post foi excluído']);
    }
} else {
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode(['error' => 'Erro ao excluir post: ' . $conn->error]);
}

$conn->close();
?>
