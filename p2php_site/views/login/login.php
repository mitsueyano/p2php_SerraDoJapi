<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
        <link rel="stylesheet" href="../default/default.css">
        <link rel="stylesheet" href="../login/login.css">
    </head>
    <body>
        <div id="header">
            <h1>ecoframe</h1>
            <span>Foto: José Aparecido dos Santos</span>
        </div>
        <div id="navbar">
            <a href="../index/index.php">INÍCIO</a>
            <a href="../explore/explore.php">EXPLORAR</a>
        <?php 
            if(isset($_SESSION['loggedin'])) {
                echo '<a href="../profile/profile.php?username=' . $_SESSION['username'] . '" id="profile-link">PERFIL</a>';
            } else {
                echo '<a href="../login/login.php" id="login-link" class="selected">ENTRE</a>';
            }
        ?>
        </div>
        <div id="content">
            <div id="box">
                <form action="../../php/loginvalidation.php" method="post">
                    <div class="flex"><label for="cpf">CPF</label><input type="text" required id="cpf" name="cpf"></div>
                    <div class="flex"><label for="password">Senha</label><input type="password" required id="senha" name="password"><a href="" id="forgotpassword">Esqueci minha senha</a></div>
                    <span id="error"></span>
                    <input type="submit" value="Entrar" id="login">
                    <a href="../signup/signup.php" id="signup">Não possui uma conta? Cadastre-se.</a>
                </form>
            </div>
        </div>
    </body>
</html>
<script src="../login/login.js"></script>