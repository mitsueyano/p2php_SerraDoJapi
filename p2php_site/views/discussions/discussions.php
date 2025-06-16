<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Discussões da Comunidade</title>
    <link rel="stylesheet" href="../default/default.css">
    <link rel="stylesheet" href="discussions.css">
    <script src="https://kit.fontawesome.com/c68ccb89e7.js" crossorigin="anonymous"></script>
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
    <div id="content">
        <div class="forum-container">
            <div class="forum-header">
                <h2>Discussões da Comunidade</h2>
                <a href="#" class="new-discussion"><i class="fa-solid fa-plus"></i> Nova Discussão</a>
            </div>
            <div id="div-tags">
                <span>Tags populares:</span>
                <button type="button" class="discussions-tags">Fungos</button>
                <button type="button" class="discussions-tags">Plantas</button>
                <button type="button" class="discussions-tags">Aves</button>
                <button type="button" class="discussions-tags">Animais Silvestres</button>
                <button type="button" class="discussions-tags">Invasoras</button>
                <button type="button" class="discussions-tags">Polinizadores</button>
                <button type="button" class="discussions-tags">Répteis</button>
                <button type="button" class="discussions-tags">Anfíbios</button>
                <button type="button" class="discussions-tags">Mamíferos</button>
                <button type="button" class="discussions-tags">Invertebrados</button>
                <button type="button" class="discussions-tags">Ecossistemas</button>
                <button type="button" class="discussions-tags">Mata Atlântica</button>
                <button type="button" class="discussions-tags">Conservação</button>
                <button type="button" class="discussions-tags">Espécies Raras</button>
                <button type="button" class="discussions-tags">Identificação</button>
            </div>
            <div id="filter">
                <div>
                    <span>Filtrar por:</span>
                    <button type="button" onclick="applyFilter('recentes')" class="selectedFilter">Recentes</button>
                    <button type="button" onclick="applyFilter('populares')">Populares</button>
                </div>
            </div>

            <div class="discussion-list">

                <div class="discussion-card"
                    onclick="window.location.href='../discussiondetails/discussiondetails.php'">
                    <div id="user-info">
                        <span><i class="fa-solid fa-user"></i> caPereira</span>
                        <span><i class="fa-solid fa-calendar"></i> 15/06/2025</span>
                    </div>
                    <div class="discussion-info">
                        <h3 class="title">Qual o papel dos fungos na floresta?</h3>
                        <p>Fiquei impressionada com a quantidade de cogumelos nos troncos das árvores. Isso indica algo
                            sobre a saúde da floresta? Ouvi dizer que eles aparecem quando a árvore está fraca ou o
                            ambiente está bem úmido.</p>
                        <div id="image-container">
                            <img src="https://cdn.pixabay.com/photo/2020/02/15/10/38/tree-fungus-4850577_960_720.jpg"
                                alt="fungo em tronco de árvore">
                            <img src="https://img.freepik.com/fotos-premium/fungos-de-cogumelos-de-prateleira-crescendo-em-tronco-de-arvore_302440-2531.jpg"
                                alt="fungos em tronco de árvore">
                            <div class="last-image-wrapper">
                                <img src="https://p1.hippopx.com/preview/857/5/237/mushrooms-log-weave-tree-fungi.jpg"
                                    alt="fungos em tronco de árvore" class="last-image">
                                <span class="overlay-text">+3</span>
                            </div>
                        </div>
                        <div class="tags">
                            <span class="tag">Fungos</span>
                            <span class="tag">Ecossistema</span>
                            <span class="tag">Curiosidades</span>
                        </div>
                    </div>
                    <div class="discussion-footer">
                        <span><i class="fa-solid fa-comments"></i> 3</span>
                        <button class="answer-btn">Responder</button>
                    </div>
                </div>


                <div class="discussion-card">
                    <div id="user-info">
                        <span><i class="fa-solid fa-user"></i> thi_vasques</span>
                        <span><i class="fa-solid fa-calendar"></i> 15/06/2025</span>
                    </div>
                    <div class="discussion-info">
                        <h3 class="title">O que fazer ao encontrar um animal silvestre?</h3>
                        <p>Vi um bicho preguiça perto da estrada. Fiquei com medo de que ele fosse atropelado. Qual é o
                            procedimento ideal nesses casos?</p>
                        <div id="image-container">
                            <img src="https://www.curiosidadesdeubatuba.com.br/ubatuba/wp-content/uploads/2017/01/Preguica-@marcele-1.jpg"
                                alt="bicho preguiça na estrada">
                            <img src="https://ogimg.infoglobo.com.br/in/6015439-6e8-84f/FT1086A/760/resgate-bicho-preguica.jpg"
                                alt="bicho preguiça na estrada">
                            <div class="last-image-wrapper">
                                <img src="https://s2.glbimg.com/_TDnd8BX-IzUYjVa2ojJO07FrzYMKmq_vvdTz3xLIMFIoz-HdGixxa_8qOZvMp3w/s.glbimg.com/jo/g1/f/original/2012/09/06/bichopreguica2_620x465.jpg"
                                    alt="bicho preguiça na estrada" class="last-image">
                                <span class="overlay-text">+1</span>
                            </div>
                        </div>
                        <div class="tags">
                            <span class="tag">Animais Silvestres</span>
                            <span class="tag">Cuidados</span>
                            <span class="tag">Conservação</span>
                        </div>
                    </div>
                    <div class="discussion-footer">
                        <span><i class="fa-solid fa-comments"></i> 9</span>
                        <button class="answer-btn">Responder</button>
                    </div>
                </div>

                <div class="discussion-card">
                    <div id="user-info">
                        <span><i class="fa-solid fa-user"></i> mariaflora</span>
                        <span><i class="fa-solid fa-calendar"></i> 14/06/2025</span>
                    </div>
                    <div class="discussion-info">
                        <h3 class="title">Essa planta é nativa ou exótica?</h3>
                        <p>Vi essa planta em um parque urbano aqui em Jundiaí e ela me chamou atenção. Como saber se é
                            uma espécie nativa ou foi introduzida na região?</p>
                        <div id="image-container">
                            <img src="https://www.floresefolhagens.com.br/wp-content/uploads/2020/11/papo-de-peru-aristolochia-gigantea-1-3.jpg"
                                alt="flor colorida">
                            <img src="https://www.floresefolhagens.com.br/wp-content/uploads/2020/11/papo-de-peru-aristolochia-gigantea-1-4.jpg"
                                alt="planta exótica">
                            <img src="https://img.elo7.com.br/product/zoom/4B343CE/sementes-de-trepadeira-papo-de-peru-gigante-trepadeira-plantas-sementes-aristolochia.jpg"
                                alt="planta exótica">

                        </div>
                        <div class="tags">
                            <span class="tag">Plantas</span>
                            <span class="tag">Identificação</span>
                            <span class="tag">Biodiversidade</span>
                        </div>
                    </div>
                    <div class="discussion-footer">
                        <span><i class="fa-solid fa-comments"></i> 6</span>
                        <button class="answer-btn">Responder</button>
                    </div>
                </div>


                <div class="discussion-card">
                    <div id="user-info">
                        <span><i class="fa-solid fa-user"></i> joaonatureza</span>
                        <span><i class="fa-solid fa-calendar"></i> 13/06/2025</span>
                    </div>
                    <div class="discussion-info">
                        <h3 class="title">Esse pássaro é nativo da Mata Atlântica?</h3>
                        <p>Encontrei essa ave com penas azuladas e um canto bem forte. Alguém sabe se ela é comum nessa
                            região ou está fora do seu habitat?</p>
                        <div id="image-container">
                            <img src="https://pick-upau.org.br/projeto_aves/especies/2019.08.23_ong-materia-sai-azul/sai-azul-materiaIMG_6261.jpg"
                                alt="ave colorida">
                            <img src="https://s3.amazonaws.com/media.wikiaves.com.br/images/9871/1789362_c381695d42420279a3ff94fbfd3203e9.jpg"
                                alt="pássaro tropical">
                        </div>
                        <div class="tags">
                            <span class="tag">Aves</span>
                            <span class="tag">Identificação</span>
                            <span class="tag">Mata Atlântica</span>
                        </div>
                    </div>
                    <div class="discussion-footer">
                        <span><i class="fa-solid fa-comments"></i> 7</span>
                        <button class="answer-btn">Responder</button>
                    </div>
                </div>
            </div>
        </div>
        <button id="backToTop" title="Voltar ao topo"><i class="fa-solid fa-arrow-up"></i></button>
    </div>
</body>
<script src="discussions.js"></script>

</html>