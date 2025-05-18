<?php
include('conectaBD.php');

if (!isset($_GET['nome'])) {
    http_response_code(400);
    echo "Nome da espécie não especificado.";
    exit;
}

$nome = $_GET['nome'];

$stmt = $conexao->prepare("
    SELECT 
    rb.url_imagem AS caminho_imagem,
    CONCAT(u.nome, ' ', u.sobrenome) AS usuario,
    rb.data_observacao,
    rb.hora_observacao,
    g.nome_lugar AS localizacao
    FROM registros_biologicos rb
    INNER JOIN usuarios u ON rb.id_usuario = u.id
    INNER JOIN classificacao_taxonomica ct ON rb.id_taxonomia = ct.id
    INNER JOIN geolocalizacao g ON rb.id_geolocalizacao = g.id
    WHERE ct.especie = ?
");
$stmt->bind_param("s", $nome);
$stmt->execute();
$resultado = $stmt->get_result();

$imagens = [];
while ($row = $resultado->fetch_assoc()) {
    $dataCompleta = $row['data_observacao'] . ' ' . $row['hora_observacao'];
    $dt = new DateTime($dataCompleta);
    $row['data'] = $dt->format('d/m/Y H:i');

    $imagens[] = [
        'caminho_imagem' => $row['caminho_imagem'],
        'usuario' => $row['usuario'],
        'data' => $row['data'],
        'localizacao' => $row['localizacao']
    ];
}


echo json_encode([
    "imagens" => $imagens,
    "link" => "https://www.inaturalist.org/search?q=" . urlencode($nome)
]);
