<?php
    include("conectaBD.php");

    $query = "SELECT 
                (SELECT COUNT(*) FROM registros_biologicos) AS total_registros,
                (SELECT COUNT(DISTINCT id_taxonomia) FROM registros_biologicos) AS total_especies,
                (SELECT COUNT(*) FROM usuarios) AS total_colaboradores";


    $resultado = mysqli_query($conexao, $query);

    if ($resultado && $row = $resultado->fetch_assoc()) {
        echo json_encode([
            "registros" => $row["total_registros"],
            "especies" => $row["total_especies"],
            "colaboradores" => $row["total_colaboradores"]
        ]);
    } else {
        var_dump($row);
        echo json_encode(["erro" => "Erro ao consultar estatísticas."]);
    }

    $conexao->close();
?>