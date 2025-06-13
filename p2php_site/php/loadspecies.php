<?php
include('connectDB.php');

$selectedcategory = isset($_GET['category']) ? $_GET['category'] : 'todos';
$validcategories = ['todos', 'fauna', 'flora', 'naoidentificado'];
if (!in_array($selectedcategory, $validcategories)) {
    $selectedcategory = 'todos';
}

if ($selectedcategory == 'todos') {
    $query = "
        SELECT DISTINCT ct.especie, ct.classe
        FROM registros_biologicos rb
        JOIN classificacao_taxonomica ct ON rb.id_taxonomia = ct.id
        WHERE rb.identificacao = TRUE
        ORDER BY ct.especie
    ";
    $result = $conn->query($query);

} else if ($selectedcategory == 'naoidentificado') {
    $query = "
        SELECT 
            'Não identificado' AS especie,
            NULL AS classe
        FROM registros_biologicos
        WHERE identificacao = FALSE
    ";
    $result = $conn->query($query);

} else {
    $query = "
        SELECT DISTINCT ct.especie, ct.classe
        FROM registros_biologicos rb
        JOIN classificacao_taxonomica ct ON rb.id_taxonomia = ct.id
        JOIN categoria c ON ct.id_categoria = c.id
        WHERE rb.identificacao = TRUE AND c.nome = ?
        ORDER BY ct.especie
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $selectedcategory);
    $stmt->execute();
    $result = $stmt->get_result();
}

$species = [];

while ($row = $result->fetch_assoc()) {
    $species[] = [
        'especie' => $row['especie'],
        'classe' => $row['classe']
    ];
}

header('Content-Type: application/json');
echo json_encode($species);
exit;
?>
