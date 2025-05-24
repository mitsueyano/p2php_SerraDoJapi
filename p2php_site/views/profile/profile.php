<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Perfil</title>
        <link rel="stylesheet" href="../default/default.css">
        <link rel="stylesheet" href="profile.css">
    </head>
    <body>
        <div id="header">
            <h1>ecoframe</h1>
            <span>Foto: José Aparecido dos Santos</span>
        </div>
        <div id="navbar">
            <a href="../index/index.php">INÍCIO</a>
            <a href="../explore/explore.php">EXPLORAR</a>
            <a href="../login/login.php" id="login-link">ENTRE / CADASTRE-SE</a>
            <a href="../profile/profile.php" id="profile-link" class="selected" style="display: none;">PERFIL</a>
        </div>
        <div id="content">
            <button id="btn-logout">Sair</button>
        </div>
    </body>
</html>
<script src="../default/showprofile.js"></script>
<script>
    document.getElementById("btn-logout").addEventListener("click", function () {
        window.location.href = "../../php/logout.php";
    });
</script>
