<?php
    require_once("conectaBD.php");

    $limite = 1;
    $offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;

    $query = "
        SELECT rb.*, u.nome, u.sobrenome, ct.especie, g.endereco 
        FROM registros_biologicos rb
        JOIN usuarios u ON rb.id_usuario = u.id
        JOIN classificacao_taxonomica ct ON rb.id_taxonomia = ct.id
        JOIN geolocalizacao g ON rb.id_geolocalizacao = g.id
        ORDER BY rb.data_observacao DESC, rb.horario_observacao DESC
        LIMIT $limite OFFSET $offset
    ";

    $result = mysqli_query($conexao, $query);

    $posts = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $posts[] = $row;
    }

    header("Content-Type: application/json"); //"Avisa" ao navegador ou à requisição fetch() que o conteúdo é JSON
    echo json_encode($posts);
?>
