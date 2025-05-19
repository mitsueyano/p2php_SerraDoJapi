    <?php
    session_start();
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Ocorrências</title>
        <link rel="stylesheet" href="../padroes/padraoPag.css" />
        <link rel="stylesheet" href="ocorrencias.css" />
    </head>

    <body>
        <div id="header">
            <h1>ECOFRAME</h1>
            <span>Foto: José Aparecido dos Santos</span>
        </div>
        <div id="navbar">
            <a href="../index/index.php">INÍCIO</a>
            <a href="../explorar/explorar.php" class="selected">EXPLORAR</a>
            <a href="../login/login.php" id="login-link">ENTRE / CADASTRE-SE</a>
            <a href="../perfil/perfil.php" id="perfil-link" style="display: none;">PERFIL</a>
        </div>
        <div id="conteudo">
            <h3>ÚLTIMAS OCORRÊNCIAS</h3>
            <div id="div-registrar-ocorrencia">
                <a href="../registrarOcorrencia/registrarOcorrencia.php">Registrar nova ocorrência</a>
            </div>
            <div id="caixa">
            </div>
            <button id="ver-mais">Ver mais</button>
        </div>
        <script src="https://kit.fontawesome.com/c68ccb89e7.js" crossorigin="anonymous"></script>
        <script src="../padroes/mostraPerfil.js"></script>
        <script src="ocorrencias.js"></script>
        <script>
            sessionStorage.setItem("logado", "<?php echo isset($_SESSION['logado']) && $_SESSION['logado'] === true ? 'true' : 'false'; ?>");
        </script>
    </body>

    </html>