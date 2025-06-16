<?php
session_start();

if ($_SESSION['loggedin'] !== true) {
    header("Location: ../login/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Discussões da Comunidade</title>
    <link rel="stylesheet" href="../default/default.css">
    <link rel="stylesheet" href="discussiondetails.css">
    <script src="https://kit.fontawesome.com/c68ccb89e7.js" crossorigin="anonymous"></script>
</head>

<body>
    <div id="header">
        <h1 onclick="window.location.href='../explore/explore.php'">ecoframe</h1>
        <span>Foto: José Aparecido dos Santos</span>
    </div>
    <div id="content">
        <div class="back">
            <a href="javascript:history.back()"><i class="fa-solid fa-arrow-left"></i></a>
        </div>
        <div class="forum-container">
            <div class="forum-header">
                <h2>Discussões da Comunidade</h2>
                <a href="#" class="new-discussion"><i class="fa-solid fa-plus"></i> Nova Discussão</a>
            </div>
            <div class="discussion-list">

                <div class="discussion-card">
                    <div id="user-info">
                        <span><i class="fa-solid fa-user"></i> <u>caPereira</u></span>
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
                            <img src="https://p1.hippopx.com/preview/857/5/237/mushrooms-log-weave-tree-fungi.jpg"
                                alt="fungos em tronco de árvore" class="last-image">
                            <img src="https://terramagna.com.br/wp-content/uploads/2022/07/fungos-tronco-arvore.png"
                                alt="fungos em tronco de árvore" class="last-image">
                            <img src="https://img.freepik.com/fotos-gratis/closeup-tiro-de-um-tronco-de-arvore-coberto-de-fungos-em-uma-floresta_181624-42180.jpg?w=360"
                                alt="fungos em tronco de árvore" class="last-image">
                        </div>
                    </div>
                    <div class="tags">
                        <span class="tag">Fungos</span>
                        <span class="tag">Ecossistema</span>
                        <span class="tag">Curiosidades</span>
                    </div>
                </div>
                <div class="comment-box">
                    <textarea placeholder="Escreva seu comentário..." rows="3"></textarea>
                    <button class="submit-comment">Enviar</button>
                </div>
                <div class="comments-section">
                    <h3>3 Comentários</h3>
                    <div class="comment has-replies">
                        <div class="comment-header">
                            <img src="https://static.ndmais.com.br/2021/02/christian-raboch.jpg" alt="Foto de perfil"
                                class="comment-profile-pic" data-username="luanagomes">
                            <span class="comment-author" data-username="rodrigocosta">Rodrigo
                                Costa</span>
                            <span class="comment-username" data-username="rodrigozcosta">@rodrigozcosta</span>
                            <span class="badge">Especialista</span>
                            <span class="comment-date">15/06/2025 às 10:30</span>
                        </div>
                        <div class="comment-content">
                            <p>Ótima observação, Carla. Os fungos são fundamentais na decomposição da
                                matéria orgânica, reciclando nutrientes para o solo. A presença de cogumelos
                                em troncos pode sim indicar que a árvore está em processo de decomposição,
                                especialmente se ela estiver fraca ou morta. Também é comum em ambientes
                                úmidos, que favorecem o crescimento de fungos.</p>
                        </div>
                        <div class="comment-actions">
                            <button class="reply-btn" onclick="showReplyForm(1)">Responder</button>
                        </div>
                        <div class="reply-form-container" id="reply-form-1" style="display: none;">
                            <textarea id="reply-input-1" placeholder="Escreva sua resposta..."></textarea>
                            <button onclick="postReply(1, 100)">Enviar resposta</button>
                        </div>
                        <div class="replies">
                            <div class="comment reply">
                                <div class="comment-header">
                                    <img src="https://conexaoplaneta.com.br/wp-content/uploads/2017/04/biologa-brasileira-ganha-premio-internacional-unesco-jovens-talentos-ciencia-2-conexao-planeta.jpg"
                                        alt="Foto de perfil" class="comment-profile-pic" data-username="rodrigocosta">
                                    <div class="flex">
                                        <span class="comment-author" data-username="mariasilva">Maria Silva</span>
                                        <span class="comment-username"
                                            data-username="rodrigocosta">@maria_silva123</span>
                                        <span class="badge">Especialista</span>
                                    </div>
                                    <span class="comment-date">15/06/2025 às 11:10</span>
                                </div>
                                <div class="comment-content">
                                    <p>É verdade. Além disso, alguns fungos ajudam as árvores a pegar mais água e
                                        nutrientes pelo solo. Isso deixa as árvores mais fortes e saudáveis. Os fungos
                                        são muito importantes para a floresta!</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button id="backToTop" title="Voltar ao topo"><i class="fa-solid fa-arrow-up"></i></button>
        </div>
</body>
<script src="discussiondetails.js"></script>

</html>