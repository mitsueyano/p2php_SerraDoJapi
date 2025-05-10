 sessionStorage.setItem("logado", "<?php echo isset($_SESSION['logado']) && $_SESSION['logado'] === true ? 'true' : 'false'; ?>");

    //---Função para carregar uma quantidade posts limitados do banco de dados >> carregarPosts.php---//

    let offset = 0;
    const limite = 1; //Limite
    const feed = document.getElementById("feed");

    function carregarPosts() {
    fetch(`../../php_funcoes/carregarPosts.php?offset=${offset}`)
            .then(response => response.json())
            .then(posts => {
                if (posts.length === 0) {
                    document.getElementById("btn-ver-mais").style.display = "none";
                    return;
                }
                posts.forEach(post => {
                    const bloco = document.createElement("div");
                    bloco.className = "bloco-post";
                    bloco.innerHTML = `
                        <div class="imagem-post"><img src="${post.url_imagem}" alt="Imagem de ${post.nome_popular}"></div>
                        <div class="texto-post">
                            <div class="flexnome">
                                <span class="nomepopular"> ${post.nome_popular}</span>
                                <span class="especie"> (${post.especie}) </span>
                            </div>                        
                            <div class="descricao">
                                <span class="descricao"> ${post.descricao} </span>
                            </div>

                            <div class="autorendereco">
                            <div class="endereco">
                                <i class="fa-solid fa-location-dot"></i>
                                <span>${post.endereco}</span>
                            </div>
                            <div class="autor">   
                                <i class="fa-solid fa-camera"></i>          
                                <span>Por ${post.nome} ${post.sobrenome}</span>
                            </div>
                        </div>
                        <div class="interacoes-post">
                            <i class="fa-solid fa-comments comentario"></i>
                            <i class="fa-solid fa-heart like"></i>
                        </div>
                    `;
                    feed.appendChild(bloco);
                });
                offset += limite;
            });
    }

    document.getElementById("btn-ver-mais").addEventListener("click", carregarPosts);
    window.addEventListener("DOMContentLoaded", carregarPosts);