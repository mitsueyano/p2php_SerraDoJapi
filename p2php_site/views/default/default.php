<?php
    session_start();
?>
<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Titulo</title>
        <link rel="stylesheet" href="../default/default.css">
    </head>
    <body>
        <div id="header">
            <h1>ecoframe</h1>
            <span>Foto: José Aparecido dos Santos</span>
        </div>
        <div id="navbar">
            <a href="../index/index.php" class="selected">INÍCIO</a>
            <a href="../explorar/explorar.php">EXPLORAR</a>
            <a href="../login/login.php" id="login-link">ENTRE / CADASTRE-SE</a>
            <a href="../perfil/perfil.php" id="perfil-link" style="display: none;">PERFIL</a>
        </div>
        <div id="conteudo">
        </div>
    </body>
</html>
<script src="../default/showprofile.js"></script>
<script>
    sessionStorage.setItem("loggedin", "<?php echo isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true ? 'true' : 'false'; ?>");
</script>