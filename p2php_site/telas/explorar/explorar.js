let offset = 0;
const limite = 1;
const feed = document.getElementById("feed");
const idusuario = sessionStorage.getItem("id_usuario");

function teste(idpost) {
    console.log(idpost, idusuario);

    if (sessionStorage.getItem("logado") !== "true") {
        console.log("Usuário não logado");
        window.location.href = "../login/login.php";
    } else {
        const likeIcon = document.getElementById(idpost);
        const likeCount = document.getElementById(`likes-${idpost}`);

        // Alterar imediatamente a classe de like
        likeIcon.classList.toggle("liked");

        // Alterar a contagem de likes localmente
        let count = parseInt(likeCount.textContent);
        likeCount.textContent = likeIcon.classList.contains("liked") ? count + 1 : count - 1;

        // Enviar a requisição para o servidor
        fetch("../../php_funcoes/atualizarLikes.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                post_id: idpost,
                usuario_id: idusuario
            })
        })
        .then((response) => {
            if (!response.ok) throw new Error("Erro HTTP " + response.status);
            return response.json();
        })
        .then((data) => {
            console.log(data);
            if (!data.success) {
                // Caso haja erro, desfaz a alteração
                likeIcon.classList.toggle("liked");
                likeCount.textContent = data.liked ? count - 1 : count + 1;
            }
        })
        .catch((error) => {
            console.error("Erro no fetch:", error);
            // Em caso de erro, desfaz a alteração local
            likeIcon.classList.toggle("liked");
            likeCount.textContent = count;
        });
    }
}

function carregarPosts() {
    fetch(`../../php_funcoes/carregarPosts.php?offset=${offset}&usuario_id=${idusuario}`)
        .then(response => response.json())
        .then(posts => {
            if (posts.length === 0) {
                document.getElementById("btn-ver-mais").style.display = "none";
                return;
            }
            posts.forEach(post => {
                const bloco = document.createElement("div");
                bloco.className = "bloco-post";

                const likeClass = post.curtiu ? "liked" : "";

                bloco.innerHTML = `
                    <div class="imagem-post"><img src="${post.url_imagem}" alt="Imagem de ${post.nome_popular}"></div>
                    <div class="texto-post">
                        <div class="flexnome">
                            <span class="nomepopular">${post.nome_popular}</span>
                            <span class="especie">(${post.especie})</span>
                        </div>                        
                        <div class="descricao">
                            <span class="descricao">${post.descricao}</span>
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
                            <i class="fa-solid fa-comments comentario"></i><span>${post.qtde_coment}</span>
                            <i class="fa-solid fa-heart like ${likeClass}" id="${post.id}" onclick="teste(this.id)"></i>
                            <span id="likes-${post.id}">${post.qtde_likes}</span>
                        </div>
                    </div>
                `;
                feed.appendChild(bloco);
            });
            offset += limite;
        });
}

document.getElementById("btn-ver-mais").addEventListener("click", carregarPosts);
window.addEventListener("DOMContentLoaded", carregarPosts);
