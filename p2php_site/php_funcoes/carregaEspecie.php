<?php
include('conectaBD.php');

$tipoSelecionado = isset($_GET['tipo']) ? $_GET['tipo'] : 'todos';
$tiposValidos = ['todos', 'animais', 'insetos', 'plantas'];
if (!in_array($tipoSelecionado, $tiposValidos)) {
    $tipoSelecionado = 'todos';
}

if ($tipoSelecionado == 'todos') {
    $query = "SELECT especie FROM classificacao_taxonomica ORDER BY especie";
    $resultado = $conexao->query($query);
} else {
    $query = "SELECT especie FROM classificacao_taxonomica WHERE tipo = ? ORDER BY especie";
    $stmt = $conexao->prepare($query);
    $stmt->bind_param("s", $tipoSelecionado);
    $stmt->execute();
    $resultado = $stmt->get_result();
}

$especies = [];
while ($row = $resultado->fetch_assoc()) {
    $especies[] = $row['especie'];
}

header('Content-Type: application/json');
echo json_encode($especies);
exit;
