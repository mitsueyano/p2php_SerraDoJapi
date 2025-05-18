<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INÍCIO</title>
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="../padroes/padraoPag.css">
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
        <div id="descricao">
            <div id="texto">
                <p id="lema">Descubra, Registre, Proteja.</p>
                <p>Bem-vindo à nossa plataforma colaborativa de catalogação da biodiversidade! Aqui, você pode registrar
                    e gerenciar dados sobre a fauna e flora local, ajudando a construir um banco de dados rico e
                    dinâmico, acessível a todos.</p>
                <p>Nossa plataforma permite que especialistas e cidadãos contribuam juntos para a preservação da
                    natureza. Registre suas observações com informações detalhadas, como nome científico e localização,
                    e contribua para o crescimento de um conhecimento compartilhado.</p>
                <p>Com uma estrutura sólida de usuários, registros biológicos e validação pela comunidade, nossa
                    ferramenta oferece uma maneira eficiente de catalogar, atualizar e corrigir dados em tempo real.
                    Vamos trabalhar juntos pela conservação e pelo futuro da biodiversidade!</p>
            </div>
            <div id="dados">
                <div class="bloco">
                    <span class="num" id="num-registros">...</span> <span>Registros</span>
                </div>
                <div class="bloco">
                    <span class="num" id="num-especies">...</span> <span>Espécies</span>
                </div>
                <div class="bloco">
                    <span class="num" id="num-colaboradores">...</span> <span>Colaboradores</span>
                </div>
            </div>
        </div>
        <div id="divtextogeral">
            <div id="imgtextogeral">
                <div id="textogeral">
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Distinctio eligendi deserunt explicabo
                        vero repellat porro. Quam sed deleniti, unde.</p>
                    <button id="btn-compartilhe">Compartilhe sua observação</button>
                </div>
            </div>
        </div>
        <div id="destaques">
            <p>Destaques da comunidade</p>
            <div id="barra-destaques"></div>
        </div>
    </div>
</body>

</html>
<script src="../padroes/mostraPerfil.js"></script>
<script src="../index/index.js"></script>
<script>
    sessionStorage.setItem("logado", "<?php echo isset($_SESSION['logado']) && $_SESSION['logado'] === true ? 'true' : 'false'; ?>");
</script>