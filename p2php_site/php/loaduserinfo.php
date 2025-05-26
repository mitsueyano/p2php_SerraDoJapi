<?php
include("connectDB.php");

$username = $_GET['username'] ?? null;

$query = "SELECT 
    u.*,
    (SELECT COUNT(*) 
     FROM registros_biologicos rb 
     WHERE rb.id_usuario = u.id) AS total_registros,

    (SELECT COUNT(*) 
     FROM ocorrencias o 
     WHERE o.id_usuario = u.id) AS total_ocorrencias,

    (SELECT COUNT(*) 
     FROM curtidas_usuarios cu
     WHERE cu.id_usuario = u.id) AS total_curtidas

FROM usuarios u
WHERE u.nome_usuario = ?;";
                
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $row = $result->fetch_assoc()) {
    header('Content-Type: application/json');
    echo json_encode([
        "usuario" => [
            "id" => $row["id"],
            "nome" => $row["nome"],
            "sobrenome" => $row["sobrenome"],
            "nome_usuario" => $row["nome_usuario"],
            "email" => $row["email"],
            "imagem" => $row["imagem_perfil"],
            "especialista" => $row["nivel_acesso"] == "especialista",
        ],
        "estatisticas" => [
            "registros" => $row["total_registros"],
            "ocorrencias" => $row["total_ocorrencias"],
            "curtidas" => $row["total_curtidas"]
        ]
    ]);
} else {
    header('Content-Type: application/json');
    echo json_encode(["erro" => "Erro ao consultar dados do usuário."]);
}

$conn->close();
?>