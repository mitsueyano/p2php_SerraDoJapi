<?php
session_start();

if ($_SESSION['loggedin'] !== true) {
    header("Location: ../login/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Registro - EcoFrame</title>
    <link rel="stylesheet" href="../default/default.css">
    <link rel="stylesheet" href="postdetails.css">
    <script src="https://kit.fontawesome.com/c68ccb89e7.js" crossorigin="anonymous"></script>
</head>
<body>
    <div id="header">
        <h1 onclick="window.location.href='../explore/explore.php'">ecoframe</h1>
        <span>Foto: José Aparecido dos Santos</span>
    </div>
    <div id="content">
        <div class="back">
            <a href="javascript:back()"><i class="fa-solid fa-arrow-left"></i></a>
        </div>
        <div id="content-post">
            <div id="post-container">
            </div>
        </div>
        <button id="backToTop" title="Voltar ao topo"><i class="fa-solid fa-arrow-up"></i></button>
    </div>
    <script src="postdetails.js"></script>
</body>
</html>