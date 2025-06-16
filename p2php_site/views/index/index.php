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
    <link rel="stylesheet" href="../default/default.css">
    <script src="https://kit.fontawesome.com/c68ccb89e7.js" crossorigin="anonymous"></script>
</head>

<body>
    <div id="header">
        <h1 onclick="window.location.href='../explore/explore.php'">ecoframe</h1>
        <span>Foto: José Aparecido dos Santos</span>
    </div>
    <div id="navbar">
        <a href="../index/index.php" class="selected">INÍCIO</a>
        <a href="../explore/explore.php">EXPLORAR</a>
        <?php
        if (isset($_SESSION['loggedin'])) {
            echo '<a href="../profile/profile.php?username=' . $_SESSION['username'] . '" id="profile-link">PERFIL</a>';
        } else {
            echo '<a href="../login/login.php" id="login-link">ENTRE</a>';
        }
        ?>
    </div>
    <div id="content">
        <div id="description">
            <div id="text">
                <p id="motto">Descubra, Registre, Proteja.</p>
                <p>Seja muito bem-vindo à nossa plataforma colaborativa de catalogação da biodiversidade, um espaço
                    pensado para unir conhecimento, ciência cidadã e preservação ambiental. Aqui, qualquer pessoa
                    interessada pode registrar e gerenciar
                    informações valiosas sobre a fauna e flora local, contribuindo diretamente para a construção de um
                    banco de dados abrangente, rico em detalhes e sempre em constante atualização.
                    Nosso compromisso é promover a participação ativa da comunidade, incentivando o compartilhamento de
                    observações com dados precisos, como nomes científicos, localização geográfica e características das
                    espécies observadas. Dessa forma, fortalecemos o conhecimento coletivo e ampliamos a compreensão
                    sobre a biodiversidade que nos cerca.
                <p>Oferecemos um ambiente onde é possível debater, trocar experiências e discutir
                    tópicos relevantes relacionados à conservação, ecossistemas e sustentabilidade. Por meio de nosso
                    fórum, usuários promovem
                    a colaboração e o engajamento,
                    tornando a plataforma um verdadeiro ponto de encontro para apaixonados pela natureza.
                    Venha fazer parte desse movimento fundamental para
                    a proteção da biodiversidade e o futuro do nosso planeta. Juntos, podemos transformar informações em
                    ações concretas pela conservação ambiental.
            </div>
            <div id="data">
                <div class="section">
                    <span class="num" id="num-records">...</span> <span>Registros</span>
                </div>
                <div class="section">
                    <span class="num" id="num-species">...</span> <span>Espécies</span>
                </div>
                <div class="section">
                    <span class="num" id="num-collaborators">...</span> <span>Colaboradores</span>
                </div>
            </div>
        </div>
        <div id="div-generaltext">
            <div id="imggeneraltext">
                <div id="generaltext">
                    <p>Ajude a documentar a vida ao seu redor. Registre aves, plantas e outros organismos que encontrar
                        na natureza. Cada observação conta para a conservação do nosso planeta.</p>
                    <a href="../share/share.php" id="btn-share">Compartilhe sua observação</a>
                </div>
            </div>
        </div>
        <div id="highlights">
            <p>Destaques da comunidade</p>
            <div id="highlights-bar"></div>
        </div>
        <button id="backToTop" title="Voltar ao topo"><i class="fa-solid fa-arrow-up"></i></button>
    </div>
</body>

</html>
<script src="../index/index.js"></script>