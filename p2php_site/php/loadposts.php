<?php
require_once("connectDB.php");

$limit = 8;
$offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
$userid = isset($_GET['userid']) ? intval($_GET['userid']) : null;

$query = "
    SELECT 
        registros_biologicos.*,
        usuarios.nome,
        usuarios.sobrenome,
        classificacao_taxonomica.especie,
        geolocalizacao.nome_lugar,
        DATE_FORMAT(registros_biologicos.data_observacao, '%d/%m/%Y') AS data_observacao,
        registros_biologicos.hora_observacao,
        DATE_FORMAT(registros_biologicos.data_publicacao, '%d/%m/%Y') AS data_publicacao,
        registros_biologicos.hora_publicacao,
        (
            SELECT COUNT(*) 
            FROM curtidas_usuarios 
            WHERE curtidas_usuarios.id_usuario = $userid 
              AND curtidas_usuarios.id_registro = registros_biologicos.id
        ) AS liked
    FROM registros_biologicos
    JOIN usuarios ON registros_biologicos.id_usuario = usuarios.id
    JOIN classificacao_taxonomica ON registros_biologicos.id_taxonomia = classificacao_taxonomica.id
    JOIN geolocalizacao ON registros_biologicos.id_geolocalizacao = geolocalizacao.id
    ORDER BY registros_biologicos.data_publicacao DESC, registros_biologicos.hora_publicacao DESC
    LIMIT $limit OFFSET $offset
";

$result = mysqli_query($conn, $query);

$posts = [];
while ($row = mysqli_fetch_assoc($result)) {
    $row['liked'] = $row['liked'] == 1 ? true : false;
    $posts[] = $row;
}

header('Content-Type: application/json');
echo json_encode($posts);
