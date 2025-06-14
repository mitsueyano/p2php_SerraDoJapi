<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INÍCIO</title>
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="../default/default.css">
    <script src="https://kit.fontawesome.com/c68ccb89e7.js" crossorigin="anonymous"></script>
</head>

<body>
    <div id="header">
        <h1 onclick="window.location.href='../explore/explore.php'">ecoframe</h1>
        <span>Foto: José Aparecido dos Santos</span>
    </div>
    <div id="navbar">
        <a href="../index/index.php" class="selected">INÍCIO</a>
        <a href="../explore/explore.php">EXPLORAR</a>
        <?php 
            if(isset($_SESSION['loggedin'])) {
                echo '<a href="../profile/profile.php?username=' . $_SESSION['username'] . '" id="profile-link">PERFIL</a>';
            } else {
                echo '<a href="../login/login.php" id="login-link">ENTRE</a>';
            }
        ?>
    </div>
    <div id="content">
        <div id="description">
            <div id="text">
                <p id="motto">Descubra, Registre, Proteja.</p>
                <p>Bem-vindo à nossa plataforma colaborativa de catalogação da biodiversidade! Aqui, você pode registrar
                    e gerenciar dados sobre a fauna e flora local, ajudando a construir um banco de dados rico e
                    dinâmico, acessível a todos.</p>
                <p>Nossa plataforma permite que especialistas e cidadãos contribuam juntos para a preservação da
                    natureza. Registre suas observações com informações detalhadas, como nome científico e localização,
                    e contribua para o crescimento de um conhecimento compartilhado.</p>
                <p>Com uma estrutura sólida de usuários, registros biológicos e validação pela comunidade, nossa
                    ferramenta oferece uma maneira eficiente de catalogar, atualizar e corrigir dados em tempo real.
                    Vamos trabalhar juntos pela conservação e pelo futuro da biodiversidade!</p>
            </div>
            <div id="data">
                <div class="section">
                    <span class="num" id="num-records">...</span> <span>Registros</span>
                </div>
                <div class="section">
                    <span class="num" id="num-species">...</span> <span>Espécies</span>
                </div>
                <div class="section">
                    <span class="num" id="num-collaborators">...</span> <span>Colaboradores</span>
                </div>
            </div>
        </div>
        <div id="div-generaltext">
            <div id="imggeneraltext">
                <div id="generaltext">
                    <p>Ajude a documentar a vida ao seu redor. Registre aves, plantas e outros organismos que encontrar na natureza. Cada observação conta para a conservação do nosso planeta.</p>
                    <a href="../share/share.php" id="btn-share">Compartilhe sua observação</a>
                </div>
            </div>
        </div>
        <div id="highlights">
            <p>Destaques da comunidade</p>
            <div id="highlights-bar"></div>
        </div>
    </div>
</body>

</html>
<script src="../index/index.js"></script>