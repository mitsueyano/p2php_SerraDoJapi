<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Perfil</title>
        <link rel="stylesheet" href="../padroes/padraoPag.css">
        <link rel="stylesheet" href="perfil.css">
    </head>
    <body>
        <div id="header">
            <h1>ecoframe</h1>
            <span>Foto: José Aparecido dos Santos</span>
        </div>
        <div id="navbar">
            <a href="../index/index.php">INÍCIO</a>
            <a href="../explorar/explorar.php">EXPLORAR</a>
            <a href="../login/login.php" id="login-link">ENTRE / CADASTRE-SE</a>
            <a href="../perfil/perfil.php" id="perfil-link" class="selected" style="display: none;">PERFIL</a>
        </div>
        <div id="conteudo">
            <button id="btn-logout">Sair</button>
        </div>
    </body>
</html>
<script src="../padroes/mostraPerfil.js"></script>
<script>
    sessionStorage.setItem("logado", "<?php echo isset($_SESSION['logado']) && $_SESSION['logado'] === true ? 'true' : 'false'; ?>");

    //Logout
    document.getElementById("btn-logout").addEventListener("click", function () {
        sessionStorage.clear();
        window.location.href = "../../php_funcoes/logout.php";
    });
</script>
