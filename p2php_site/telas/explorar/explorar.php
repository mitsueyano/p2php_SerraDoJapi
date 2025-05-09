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
        <title>Explorar</title>
        <link rel="stylesheet" href="../padroes/padraoPag.css">
        <link rel="stylesheet" href="explorar.css">
    </head>
    <body>
        <div id="header">
            <h1>ECOFRAME</h1>
            <span>Foto: José Aparecido dos Santos</span>
        </div>
        <div id="navbar">
            <a href="../index/index.php">INÍCIO</a>
            <a href="../explorar/explorar.php" class="selected"">EXPLORAR</a>
            <a href="../login/login.php" id="login-link">ENTRE / CADASTRE-SE</a>
            <a href="../perfil/perfil.php" id="perfil-link" style="display: none;">PERFIL</a>
        </div>
        <div id="conteudo">
            <p id="pergunta">O que você quer observar hoje?</p>
            <div id="div-barra-pesquisa">
                <div id="barra-pesquisa">
                    <input type="text" name="" id="input-pesquisa" placeholder="Pesquise...">
                </div>
                <div class="flex">
                    <div class="divopcao">
                        <img src="../img/especies_HanifiSarikaya.jpg" alt="Imagem de uma abelha polinizando">
                        <span>Espécies</span>
                    </div>
                    <div class="divopcao">
                        <img src="../img/placaanimal.png" alt="Imagem de uma abelha polinizando">
                        <span>Ocorrências</span>
                    </div>
                </div>
            </div>
            <div id="divfeed">
                <div id="navbar-feed">
                    <span id="texto">Registros da comunidade</span>
                    <div id="filtro">
                        <span>Filtrar por:</span>
                        <a href="">Recentes</a>
                        <a href="">Populares</a>
                    </div>
                </div>
                <div id="feed">
                    <div class="bloco-post">
                        <div class="imagem-post"><img src="../img/passaroexemplo.jpg" alt="passaroteste"></div>
                        <div class="texto-post"><span>Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse vero necessitatibus cum, odit quo enim incidunt. At earum reprehenderit iusto, minus voluptas delectus et tenetur error accusantium pariatur placeat facilis.</span></div>
                        <div class="interacoes-post"></div>
                    </div>
                    <div class="bloco-post">
                        <div class="imagem-post"><img src="../img/passaroexemplo.jpg" alt="passaroteste"></div>
                        <div class="texto-post"></div>
                        <div class="interacoes-post"></div>
                    </div>
                    <div class="bloco-post">
                        <div class="imagem-post"><img src="../img/passaroexemplo.jpg" alt="passaroteste"></div>
                        <div class="texto-post"></div>
                        <div class="interacoes-post"></div>
                    </div>
                    <div class="bloco-post">
                        <div class="imagem-post"><img src="../img/passaroexemplo.jpg" alt="passaroteste"></div>
                        <div class="texto-post"></div>
                        <div class="interacoes-post"></div>
                    </div>
                    <div class="bloco-post">
                        <div class="imagem-post"><img src="../img/passaroexemplo.jpg" alt="passaroteste"></div>
                        <div class="texto-post"></div>
                        <div class="interacoes-post"></div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
<script src="../padroes/mostraPerfil.js"></script>
<script>
    sessionStorage.setItem("logado", "<?php echo isset($_SESSION['logado']) && $_SESSION['logado'] === true ? 'true' : 'false'; ?>");
</script>