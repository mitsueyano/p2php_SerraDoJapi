<?php
include("connectDB.php");
session_start();
if ($_SESSION['access'] && $_SESSION['access'] != "especialista") die(401);

$query = "SELECT n.id, n.titulo_ocorrencia, n.descricao_ocorrencia, u.nome AS autor, n.sensivel, 
                 n.img_url_ocorrencia, g.nome_lugar, DATE_FORMAT(n.data_publicacao, '%d/%m/%Y') AS data, n.hora_publicacao
          FROM ocorrencias n
          INNER JOIN usuarios u ON n.id_usuario = u.id
          INNER JOIN geolocalizacao g ON n.id_geolocalizacao = g.id
          WHERE n.exibicao = false
          ORDER BY n.data_publicacao DESC, n.hora_publicacao DESC
          LIMIT 10";

$result = mysqli_query($conn, $query);

$incidents = [];

while ($row = mysqli_fetch_assoc($result)) {
    $incidents[] = [
        'id' => $row['id'],
        'titulo' => $row['titulo_ocorrencia'],
        'descricao' => $row['descricao_ocorrencia'],
        'autor' => $row['autor'],
        'nome_lugar' => $row['nome_lugar'],
        'sensivel' => (bool)$row['sensivel'],
        'img_url' => $row['img_url_ocorrencia'],
        'data' => $row['data'],
        'hora' => $row['hora_publicacao'],
    ];
}

header('Content-Type: application/json');
echo json_encode($incidents);
?>
