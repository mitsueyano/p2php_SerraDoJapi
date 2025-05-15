<?php
    session_start();

    if (!isset($_SESSION["logado"]) || $_SESSION["logado"] != TRUE) {
    }
    else {
        echo "<h1>Seja bem-vindo, ".$_SESSION["user"]."</h1>";
    }
?>
<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Espécies</title>
        <link rel="stylesheet" href="../padroes/padraoPag.css">
        <link rel="stylesheet" href="especies.css">
    </head>
    <body>
        <div id="header">
            <h1>ECOFRAME</h1>
            <span>Foto: José Aparecido dos Santos</span>
        </div>
        <div id="navbar">
            <a href="../index/index.php" class="selected">INÍCIO</a>
            <a href="../explorar/explorar.php">EXPLORAR</a>
            <a href="../login/login.php" id="login-link">ENTRE / CADASTRE-SE</a>
            <a href="../perfil/perfil.php" id="perfil-link" style="display: none;">PERFIL</a>
        </div>
        <div id="conteudo">
            <h2>Espécies</h2>
            <div id="barra-lateral">
            </div>
        </div>
    </body>
</html>
<script src="../padroes/mostraPerfil.js"></script>
<script>
    sessionStorage.setItem("logado", "<?php echo isset($_SESSION['logado']) && $_SESSION['logado'] === true ? 'true' : 'false'; ?>");
</script>