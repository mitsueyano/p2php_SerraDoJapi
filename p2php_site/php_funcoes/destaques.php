<?php
    require_once("conectaBD.php");

    $limite = 8;

    $query = "
        SELECT rb.*, u.nome, u.sobrenome, ct.especie, g.nome_lugar,
            (SELECT COUNT(*) FROM curtidas_usuarios cu WHERE cu.id_registro = rb.id) AS qtde_likes
        FROM registros_biologicos rb
        JOIN usuarios u ON rb.id_usuario = u.id
        JOIN classificacao_taxonomica ct ON rb.id_taxonomia = ct.id
        JOIN geolocalizacao g ON rb.id_geolocalizacao = g.id
        ORDER BY qtde_likes DESC, rb.data_observacao DESC
        LIMIT $limite
    ";

    $result = mysqli_query($conexao, $query);
    $destaques = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $destaques[] = $row;
    }

    header("Content-Type: application/json");
    echo json_encode($destaques);
?>
