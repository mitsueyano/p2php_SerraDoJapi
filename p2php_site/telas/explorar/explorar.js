let offset = 0;
const limite = 8;
const feed = document.getElementById("feed");
const idusuario = sessionStorage.getItem("id_usuario");

function like(idpost) {
    console.log(idpost, idusuario);

    if (sessionStorage.getItem("logado") !== "true") {
        console.log("Usuário não logado");
        window.location.href = "../login/login.php";
    } else {
        const likeIcon = document.getElementById(idpost);
        const likeCount = document.getElementById(`likes-${idpost}`);

        likeIcon.classList.toggle("liked");

        let count = parseInt(likeCount.textContent);
        likeCount.textContent = likeIcon.classList.contains("liked") ? count + 1 : count - 1;

        fetch("../../php_funcoes/atualizarLikes.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ post_id: idpost, usuario_id: idusuario })
        })
        .then((response) => {
            if (!response.ok) throw new Error("Erro HTTP " + response.status);
            return response.json();
        })
        .then((data) => {
            console.log(data);
            if (!data.success) {
                likeIcon.classList.toggle("liked");
                likeCount.textContent = data.liked ? count - 1 : count + 1;
            }
        })
        .catch((error) => {
            console.error("Erro no fetch:", error);
            likeIcon.classList.toggle("liked");
            likeCount.textContent = count;
        });
    }
}

// Funções do modal para imagem
function abrirModal(urlImagem) {
    const modal = document.getElementById("modal");
    const img = document.getElementById("imgModal");
    img.src = urlImagem;
    modal.style.display = "block";
}

function fecharModal() {
    document.getElementById("modal").style.display = "none";
}
function formatarDataHoraCompleta(dataStr, horaStr) {
    // dataStr no formato dd/mm/yyyy, horaStr no formato hh:mm:ss ou hh:mm
    const partes = dataStr.split('/');
    if (partes.length !== 3) return dataStr + " às " + horaStr.slice(0,5);

    const dataObj = new Date(partes[2], partes[1] - 1, partes[0]);
    
    const hoje = new Date();
    hoje.setHours(0,0,0,0);
    const ontem = new Date(hoje);
    ontem.setDate(hoje.getDate() - 1);

    if (dataObj.getTime() === hoje.getTime()) {
        return `Hoje às ${horaStr.slice(0,5)}`;
    } else if (dataObj.getTime() === ontem.getTime()) {
        return `Ontem às ${horaStr.slice(0,5)}`;
    } else {
        return `${dataStr} às ${horaStr.slice(0,5)}`;
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

                const dataHoraPub = formatarDataHoraCompleta(post.data_publicacao, post.hora_publicacao);
                const dataHoraObs = formatarDataHoraCompleta(post.data_observacao, post.hora_observacao);

                bloco.innerHTML = `
                    <span id="datahora">${dataHoraPub}</span>
                    <div class="imagem-post">
                        <img src="${post.url_imagem}" alt="Imagem de ${post.nome_popular}" draggable="false" onclick="abrirModal('${post.url_imagem}')">
                    </div>
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
                                <span>${post.nome_lugar}</span>
                            </div>
                            <div class="autor">   
                                <i class="fa-solid fa-camera"></i>          
                                <span>Por ${post.nome} ${post.sobrenome}</span>
                            </div>
                            <div class="autor">   
                                <i class="fa-solid fa-calendar"></i>       
                                <span>${dataHoraObs}</span>
                            </div>
                        </div>

                        <div class="interacoes-post">
                            <i class="fa-solid fa-comments comentario"></i><span>${post.qtde_coment}</span>
                            <i class="fa-solid fa-heart like ${likeClass}" id="${post.id}" onclick="like(this.id)"></i>
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
