<?php
// check-username.php
header("Content-Type: application/json");
include '../php/connectDB.php';

// Verifica se o username foi enviado via GET
if (!isset($_GET['username'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Username não fornecido']);
    exit;
}

$username = trim($_GET['username']);

// Validações básicas
if (empty($username)) {
    echo json_encode(['available' => false, 'message' => 'Username não pode estar vazio']);
    exit;
}

if (strlen($username) < 3) {
    echo json_encode(['available' => false, 'message' => 'Username deve ter pelo menos 3 caracteres']);
    exit;
}

try {
    // Verifica se o username já existe
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE nome_usuario = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    
    $available = $stmt->num_rows === 0;
    
    // Mensagens personalizadas
    $message = $available 
        ? 'Username disponível' 
        : 'Username já está em uso';
    
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
        'message' => 'Erro no servidor ao verificar username',
        'error' => $e->getMessage()
    ]);
}
?>