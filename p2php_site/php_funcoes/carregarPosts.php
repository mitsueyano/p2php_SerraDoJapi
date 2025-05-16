<?php
require_once("conectaBD.php");

$limite = 8;
$offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
$usuario_id = isset($_GET['usuario_id']) ? intval($_GET['usuario_id']) : null;

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
            WHERE curtidas_usuarios.id_usuario = $usuario_id 
              AND curtidas_usuarios.id_registro = registros_biologicos.id
        ) AS curtiu
    FROM registros_biologicos
    JOIN usuarios ON registros_biologicos.id_usuario = usuarios.id
    JOIN classificacao_taxonomica ON registros_biologicos.id_taxonomia = classificacao_taxonomica.id
    JOIN geolocalizacao ON registros_biologicos.id_geolocalizacao = geolocalizacao.id
    ORDER BY registros_biologicos.data_publicacao DESC, registros_biologicos.hora_publicacao DESC
    LIMIT $limite OFFSET $offset
";

$result = mysqli_query($conexao, $query);

$posts = [];
while ($row = mysqli_fetch_assoc($result)) {
    $row['curtiu'] = $row['curtiu'] == 1 ? true : false;
    $posts[] = $row;
}

header('Content-Type: application/json');
echo json_encode($posts);
