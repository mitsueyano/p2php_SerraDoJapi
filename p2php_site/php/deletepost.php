<?php
session_start();
require_once("connectDB.php");
mysqli_set_charset($conn, "utf8mb4");

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    header('Content-Type: application/json');
    http_response_code(405);
    echo json_encode(['error' => 'Método não permitido. Use DELETE.']);
    exit;
}

if (!isset($_SESSION['userid'])) {
    header('Content-Type: application/json');
    http_response_code(401);
    echo json_encode(['error' => 'Usuário não autenticado']);
    exit;
}

$urlParts = explode('/', $_SERVER['REQUEST_URI']);
$postId = (int) end($urlParts);

if ($postId <= 0) {
    header('Content-Type: application/json');
    http_response_code(400);
    echo json_encode(['error' => 'ID do post inválido']);
    exit;
}

$stmtCheck = $conn->prepare("SELECT id_usuario FROM registros_biologicos WHERE id = ?");
$stmtCheck->bind_param("i", $postId);
$stmtCheck->execute();
$resultCheck = $stmtCheck->get_result();

if ($resultCheck->num_rows === 0) {
    header('Content-Type: application/json');
    http_response_code(404);
    echo json_encode(['error' => 'Post não encontrado']);
    exit;
}

$postOwner = $resultCheck->fetch_assoc();

if ($postOwner['id_usuario'] != $_SESSION['userid']) {
    header('Content-Type: application/json');
    http_response_code(403);
    echo json_encode(['error' => 'Você não tem permissão para excluir este post']);
    exit;
}

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