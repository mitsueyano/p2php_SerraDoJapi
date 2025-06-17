<?php
    require_once("connectDB.php");

    $limit = 8; //Limite de posts exibidos

    $query = "
        SELECT rb.*, u.nome AS nomeusuario, u.sobrenome, ct.especie AS especie, ct.nome_popular,
            (SELECT COUNT(*) FROM curtidas_usuarios cu WHERE cu.id_registro = rb.id) AS qtde_likes
        FROM registros_biologicos rb
        JOIN usuarios u ON rb.id_usuario = u.id
        JOIN classificacao_taxonomica ct ON rb.id_taxonomia = ct.id
        ORDER BY qtde_likes DESC, rb.data_observacao DESC
        LIMIT $limit
    ";
    
    $result = mysqli_query($conn, $query);
    $highlights = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $highlights[] = $row;
    }
    header("Content-Type: application/json");
    echo json_encode($highlights);
?>
