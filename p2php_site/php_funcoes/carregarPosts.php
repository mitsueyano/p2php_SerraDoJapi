<?php
require_once("conectaBD.php");

$limite = 1;
$offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
$usuario_id = isset($_GET['usuario_id']) ? intval($_GET['usuario_id']) : null;

$query = "
    SELECT rb.*, u.nome, u.sobrenome, ct.especie, g.endereco,
        (SELECT COUNT(*) FROM curtidas_usuarios cu WHERE cu.id_usuario = $usuario_id AND cu.id_registro = rb.id) AS curtiu
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
    // Converte o número 0 ou 1 em booleano para facilitar o uso no JS
    $row['curtiu'] = $row['curtiu'] == 1 ? true : false;
    $posts[] = $row;
}

header("Content-Type: application/json");
echo json_encode($posts);
?>
