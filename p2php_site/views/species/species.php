<?php
session_start();
include('../../php/connectDB.php');
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
    <script src="https://kit.fontawesome.com/c68ccb89e7.js" crossorigin="anonymous"></script>
</head>

<body>
    <div id="header">
        <h1 onclick="window.location.href='../explore/explore.php'">ecoframe</h1>
        <span>Foto: José Aparecido dos Santos</span>
    </div>
    <div id="navbar">
        <a href="../index/index.php">INÍCIO</a>
        <a href="../explore/explore.php" class="selected">EXPLORAR</a>
        <?php 
            if(isset($_SESSION['loggedin'])) {
                echo '<a href="../profile/profile.php?username=' . $_SESSION['username'] . '" id="profile-link">PERFIL</a>';
            } else {
                echo '<a href="../login/login.php" id="login-link">ENTRE</a>';
            }
        ?>
    </div>
    <div id="content">
        <span id="title">Espécies registradas</span>
        <div id="bar">
            <div class="flex" id="filter">
                <button id="btn-all" class="active" value="todos">Todos</button>
                <button id="btn-fauna" value="fauna">Fauna</button>
                <button id="btn-flora" value="flora">Flora</button>
                <button id="btn-notidentified" value="naoidentificado">Espécies não identificadas</button>
            </div>
            <div id="div-search-bar">
                <input type="text" name="" id="search-bar" placeholder="Procure por nome ou classe da espécie...">
                <i class="fa-solid fa-magnifying-glass"></i>
            </div>
        </div>
        <div id="box">
            <div id="species-list">
                <div id="navbar-letters">
                    <?php foreach ($letters as $letter): ?>
                        <a href="#letter-marker-<?php echo $letter; ?>" data-letter="<?php echo $letter; ?>"><?php echo $letter; ?></a>
                    <?php endforeach; ?>
                </div>
                <div id="nospecies" class="hidden">
                    <p>Nenhuma espécie encontrada.</p>
                </div>
                <div id="species-items">
                    <!-- Aqui o JS vai colocar as espécies carregadas -->
                </div>
            </div>
            <div id="div-info-specie">
            </div>
        </div>
    </div>
    <script src="species.js"></script>
</body>

</html>
