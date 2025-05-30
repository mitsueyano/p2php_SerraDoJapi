<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explorar</title>
    <link rel="stylesheet" href="../default/default.css">
    <link rel="stylesheet" href="explore.css">
    <script src="https://kit.fontawesome.com/c68ccb89e7.js" crossorigin="anonymous"></script>
</head>

<body>
    <div id="header">
        <h1>ecoframe</h1>
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
        <p id="title">O que você quer observar hoje?</p>

        <div id="div-search-bar">
            <!--
            <div id="search-bar">
                <input type="text" id="input-search-bar" placeholder="Pesquise...">
            </div>
            -->
            <div class="flex">
                <div class="div-option" id="option-species" onclick="window.location.href ='../species/species.php';">
                    <img src="../img/species_HanifiSarikaya.jpg" alt="Imagem de uma abelha polinizando">
                    <div><span>Espécies</span></div>
                </div>
                <div class="div-option" id="option-incidents"
                    onclick="window.location.href ='../incidents/incidents.php';">
                    <img src="../img/animalsign.png" alt="Placa de animal na pista">
                    <div><span>Ocorrências</span></div>
                </div>
            </div>
        </div>

        <div id="div-feed">
            <div id="navbar-feed">
                <span id="text">Registros da comunidade</span>
                <div id="filter">
                    <span>Filtrar por:</span>
                    <button type="button" onclick="applyFilter('recentes')">Recentes</button>
                    <button type="button" onclick="applyFilter('populares')">Populares</button>
                </div>
            </div>

            <div id="share">
                <a href="../share/share.php" id="btn-share">Compartilhe sua Observação</a>
            </div>

            <div id="feed"></div>

            <div id="div-see-more">
                <button id="btn-see-more">Ver mais</button>
            </div>
        </div>

        <div id="modal" class="modal" onclick="closeModal()">
            <span class="close" onclick="closeModal()"><i class="fa-solid fa-x"></i></span>
            <img class="modal-content" id="imgModal" draggable="false">
        </div>
    </div>
    <script src="explore.js"></script>
</body>
</html>