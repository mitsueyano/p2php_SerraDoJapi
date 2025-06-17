<?php
//Verificação do nome de usuário na criação de uma conta

header("Content-Type: application/json");
include '../php/connectDB.php';

if (!isset($_GET['username'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'messagNome de usuário não fornecido']);
    exit;
}
$username = trim($_GET['username']);
if (empty($username)) {
    echo json_encode(['available' => false, 'message' => 'Nome de usuário não pode estar vazio']);
    exit;
}
if (strlen($username) < 3) {
    echo json_encode(['available' => false, 'message' => 'Nome de usuário deve ter pelo menos 3 caracteres']);
    exit;
}
try {
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE nome_usuario = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    $available = $stmt->num_rows === 0;

    $message = $available
        ? 'Nome de usuário disponível'
        : 'Nome de usuário já está em uso';

    echo json_encode([
        'success' => true,
        'available' => $available,
        'message' => $message
    ]);

    $stmt->close();
    $conn->close();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro no servidor ao verificar o nome de usuário',
        'error' => $e->getMessage()
    ]);
}
?>