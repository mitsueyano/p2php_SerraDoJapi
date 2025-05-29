<?php
session_start();
header("Content-Type: application/json");
include 'connectDB.php';

// Verificação de acesso - apenas especialistas podem excluir
if (!isset($_SESSION['access']) || $_SESSION['access'] != "especialista") {
    http_response_code(403);
    echo json_encode(["success" => false, "mensagem" => "Acesso restrito a especialistas"]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "DELETE") {
    // Obter o ID da ocorrência da URL (DELETE /api/ocorrencias/{id})
    $urlParts = explode('/', $_SERVER['REQUEST_URI']);
    $incidentId = end($urlParts);
    
    // Validação do ID
    if (!is_numeric($incidentId)) {
        http_response_code(400);
        echo json_encode(["success" => false, "mensagem" => "ID inválido"]);
        exit;
    }

    $incidentId = intval($incidentId);

    try {
        // Verifica se a ocorrência existe
        $stmtCheck = $conn->prepare("SELECT id FROM ocorrencias WHERE id = ?");
        $stmtCheck->bind_param("i", $incidentId);
        $stmtCheck->execute();
        $stmtCheck->store_result();

        if ($stmtCheck->num_rows === 0) {
            http_response_code(404);
            echo json_encode(["success" => false, "mensagem" => "Ocorrência não encontrada"]);
            exit;
        }
        $stmtCheck->close();

        // Exclui a ocorrência
        $stmtDelete = $conn->prepare("DELETE FROM ocorrencias WHERE id = ?");
        $stmtDelete->bind_param("i", $incidentId);
        
        if ($stmtDelete->execute()) {
            // Verifica se alguma linha foi afetada
            if ($stmtDelete->affected_rows > 0) {
                echo json_encode([
                    "success" => true,
                    "mensagem" => "Ocorrência excluída com sucesso",
                    "id" => $incidentId
                ]);
            } else {
                http_response_code(500);
                echo json_encode(["success" => false, "mensagem" => "Nenhuma ocorrência foi excluída"]);
            }
        } else {
            http_response_code(500);
            echo json_encode(["success" => false, "mensagem" => "Erro ao excluir ocorrência", "erro" => $stmtDelete->error]);
        }
        
        $stmtDelete->close();
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["success" => false, "mensagem" => "Erro no servidor", "erro" => $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(["success" => false, "mensagem" => "Método não permitido"]);
}

$conn->close();
?>