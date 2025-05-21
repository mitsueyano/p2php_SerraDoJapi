<?php
include('connectDB.php');

$selectedType = isset($_GET['type']) ? $_GET['type'] : 'todos';
$validTypes = ['todos', 'animais', 'insetos', 'plantas'];
if (!in_array($selectedType, $validTypes)) {
    $selectedType = 'todos';
}

if ($selectedType == 'todos') {
    $query = "SELECT especie FROM classificacao_taxonomica ORDER BY especie";
    $result = $conn->query($query);
} else {
    $query = "SELECT especie FROM classificacao_taxonomica WHERE tipo = ? ORDER BY especie";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $selectedType);
    $stmt->execute();
    $result = $stmt->get_result();
}

$species = [];
while ($row = $result->fetch_assoc()) {
    $species[] = $row['especie'];
}

header('Content-Type: application/json');
echo json_encode($species);
exit;
