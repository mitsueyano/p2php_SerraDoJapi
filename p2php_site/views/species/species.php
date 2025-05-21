<?php
session_start();
include('../../php/connectDB.php');

$selectedType = isset($_GET['type']) ? $_GET['type'] : 'all';
$validTypes = ['all', 'animals', 'insects', 'plants'];
if (!in_array($selectedType, $validTypes)) {
    $selectedType = 'all';
}

if ($selectedType == 'all') {
    $query = "SELECT especie, tipo FROM classificacao_taxonomica ORDER BY especie";
} else {
    $query = "SELECT especie, tipo FROM classificacao_taxonomica WHERE tipo = ? ORDER BY especie";
}

if ($selectedType == 'all') {
    $result = $conn->query($query);
} else {
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $selectedType);
    $stmt->execute();
    $result = $stmt->get_result();
}

$species = [];
while ($row = $result->fetch_assoc()) {
    $letter = strtoupper($row['especie'][0]);
    $species[$letter][] = $row['especie'];
}

$letters = range('A', 'Z');
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Espécies</title>
    <link rel="stylesheet" href="../default/default.css">
    <link rel="stylesheet" href="species.css" />
</head>
<body>
    <div id="header">
        <h1>ecoframe</h1>
        <span>Foto: José Aparecido dos Santos</span>
    </div>
    <div id="navbar">
        <a href="../index/index.php" class="selected">INÍCIO</a>
        <a href="../explore/explore.php">EXPLORAR</a>
        <a href="../login/login.php" id="login-link">ENTRE / CADASTRE-SE</a>
        <a href="../profile/profile.php" id="profile-link" style="display:none;">PERFIL</a>
    </div>
    <div id="content">
        <span id="title">ESPÉCIES REGISTRADAS</span>
        <div id="bar">
            <div class="flex" id="filter">
                <button id="btn-all" class="active" value="todos">Todos</button>
                <button id="btn-animals" value="animais">Animais</button>
                <button id="btn-insects" value="insetos">Insetos</button>
                <button id="btn-plants" value="plantas">Plantas</button>

            </div>
        </div>
        <div id="box">
            <div id="species-list">
                <div id="navbar-letters">
                    <?php foreach ($letters as $letter): ?>
                        <a href="#letter-marker-<?php echo $letter; ?>"><?php echo $letter; ?></a>
                    <?php endforeach; ?>
                </div>
                <div id="nospecies" class="hidden">
                    <p>Nenhuma espécie encontrada.</p>
                </div>
            </div>
            <div id="div-info-specie"></div>
        </div>
    </div>
    <script src="../default/showprofile.js"></script>
    <script src="species.js"></script>
    <script>
        sessionStorage.setItem("loggedin", "<?php echo isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true ? 'true' : 'false'; ?>");
    </script>
</body>

</html>