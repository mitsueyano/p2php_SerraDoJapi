<?php
include("conectaBD.php");

$sql = "SELECT n.titulo_ocorrencia, n.descricao_ocorrencia, u.nome AS autor, n.sensivel, 
               n.img_url_ocorrencia, g.nome_lugar, n.data_publicacao, n.hora_publicacao
        FROM ocorrencias n
        INNER JOIN usuarios u ON n.id_usuario = u.id
        INNER JOIN geolocalizacao g ON n.id_geolocalizacao = g.id
        ORDER BY n.data_publicacao DESC, n.hora_publicacao DESC
        LIMIT 10";

$resultado = mysqli_query($conexao, $sql);

$ocorrencias = [];

while ($linha = mysqli_fetch_assoc($resultado)) {
    $ocorrencias[] = [
        'titulo' => $linha['titulo_ocorrencia'],
        'descricao' => $linha['descricao_ocorrencia'],
        'autor' => $linha['autor'],
        'nome_lugar' => $linha['nome_lugar'],
        'sensivel' => (bool)$linha['sensivel'],
        'img_url' => $linha['img_url_ocorrencia'],
        'data' => $linha['data_publicacao'],
        'hora' => $linha['hora_publicacao'],
    ];
}

header('Content-Type: application/json');
echo json_encode($ocorrencias);
?>
