<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Ocorrências</title>
    <link rel="stylesheet" href="../default/default.css">
    <link rel="stylesheet" href="incidents.css" />
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
        if (isset($_SESSION['loggedin'])) {
            echo '<a href="../profile/profile.php?username=' . $_SESSION['username'] . '" id="profile-link">PERFIL</a>';
        } else {
            echo '<a href="../login/login.php" id="login-link">ENTRE</a>';
        }
        ?>
    </div>
    <div id="content">
        <h3>ÚLTIMAS OCORRÊNCIAS</h3>
        <div id="div-incident-report">
            <a href="../incidentreport/incidentreport.php">Registrar nova ocorrência</a>
            <?php
            if (isset($_SESSION['access']) && $_SESSION['access'] == "especialista") {
                echo '<a href="../incidentsmanagement/incidentsmanagement.php">Gerenciar ocorrências</a>';
            }
            ?>
        </div>
        <div id="box">
        </div>
        <button id="btn-see-more">Ver mais</button>
    </div>
    <script src="https://kit.fontawesome.com/c68ccb89e7.js" crossorigin="anonymous"></script>
    <script src="incidents.js"></script>
</body>

</html>