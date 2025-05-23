<?php
include('connectDB.php');

$selectedcategory = isset($_GET['category']) ? $_GET['category'] : 'todos';
$validcategories = ['todos', 'fauna', 'flora'];
if (!in_array($selectedcategory, $validcategories)) {
    $selectedcategory = 'todos';
}

if ($selectedcategory == 'todos') {
    $query = "SELECT especie, classe FROM classificacao_taxonomica ORDER BY especie";
    $result = $conn->query($query);
} else {
    $query = "
        SELECT ct.especie, ct.classe
        FROM classificacao_taxonomica ct
        JOIN categoria c ON ct.id_categoria = c.id
        WHERE c.nome = ?
        ORDER BY ct.especie
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $selectedcategory);
    $stmt->execute();
    $result = $stmt->get_result();
}

$species = [];
$totalSpecies = [];
while ($row = $result->fetch_assoc()) {
    $species[] = [
        'especie' => $row['especie'],
        'classe' => $row['classe']
    ];
}

header('Content-Type: application/json');
echo json_encode($species);
exit;
