<?php
session_start();
header("Content-Type: application/json");
include 'connectDB.php';

// Verifica se o usuário tem acesso de especialista
if (!isset($_SESSION['access'])) {
    http_response_code(401);
    echo json_encode(["success" => false, "mensagem" => "Acesso não autorizado."]);
    exit;
}

if ($_SESSION['access'] != "especialista") {
    http_response_code(403);
    echo json_encode(["success" => false, "mensagem" => "Acesso restrito a especialistas."]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    
    // Validação dos dados recebidos
    if (!$data || !isset($data['id_ocorrencia'])) {
        http_response_code(400);
        echo json_encode(["success" => false, "mensagem" => "Dados inválidos. ID da ocorrência é obrigatório."]);
        exit;
    }

    $id_ocorrencia = intval($data['id_ocorrencia']);

    try {
        // Prepara e executa a atualização
        $stmt = $conn->prepare("UPDATE ocorrencias SET exibicao = TRUE WHERE id = ?");
        $stmt->bind_param("i", $id_ocorrencia);
        
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                echo json_encode(["success" => true, "mensagem" => "Ocorrência aprovada com sucesso."]);
            } else {
                http_response_code(404);
                echo json_encode(["success" => false, "mensagem" => "Nenhuma ocorrência encontrada com o ID fornecido."]);
            }
        } else {
            http_response_code(500);
            echo json_encode(["success" => false, "mensagem" => "Erro ao atualizar a ocorrência.", "erro" => $stmt->error]);
        }
        
        $stmt->close();
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["success" => false, "mensagem" => "Erro no servidor.", "erro" => $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(["success" => false, "mensagem" => "Método não permitido."]);
}

$conn->close();
?>