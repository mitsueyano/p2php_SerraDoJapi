<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Título</title>
    <link rel="stylesheet" href="../default/default.css">
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
        if (isset($_SESSION['loggedin'])) {
            echo '<a href="../profile/profile.php?username=' . $_SESSION['username'] . '" id="profile-link">PERFIL</a>';
        } else {
            echo '<a href="../login/login.php" id="login-link">ENTRE</a>';
        }
        ?>
    </div>
    <div id="conteudo">
        
    </div>
</body>

</html>
<script src="../default/showprofile.js"></script>