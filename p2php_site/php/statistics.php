<?php
include("connectDB.php");

$query = "SELECT 
                (SELECT COUNT(*) FROM registros_biologicos) AS total_registros,
                (SELECT COUNT(DISTINCT id_taxonomia) FROM registros_biologicos) AS total_especies,
                (SELECT COUNT(*) FROM usuarios) AS total_colaboradores";


$result = mysqli_query($conn, $query);

if ($result && $row = $result->fetch_assoc()) {
    header('Content-Type: application/json');
    echo json_encode([
        "registros" => $row["total_registros"],
        "especies" => $row["total_especies"],
        "colaboradores" => $row["total_colaboradores"]
    ]);
} else {
    header('Content-Type: application/json');
    echo json_encode(["erro" => "Erro ao consultar estatísticas."]);
}

$conn->close();
?>