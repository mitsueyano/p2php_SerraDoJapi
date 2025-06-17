let offset = 0; //Variável para controlar o índice inicial dos posts carregados (paginacao)
let currentFilter = "recentes"; //\filtro atual para os posts, padrão é "recentes"

const limit = 12; //Limite de posts para carregar por requisição
const feed = document.getElementById("feed");

function like(postid) { //Função param o clique no botão de like de um post
  const likeIcon = document.getElementById(postid); //Obtém o ícone do like pelo ID do post
  const likeCount = document.getElementById(`likes-${postid}`); //Obtém o elemento que mostra a quantidade de likes

  likeIcon.classList.toggle("liked"); //Alterna a classe "liked" para mudar o estilo do ícone (curtido/não curtido)
  let count = parseInt(likeCount.textContent);

  // Atualiza visualmente a contagem de likes, somando ou subtraindo 1 conforme estado da classe "liked"
  likeCount.textContent = likeIcon.classList.contains("liked")
    ? count + 1
    : count - 1;

  fetch("../../php/likeupdate.php", { //Envia a atualização do like para o servidor
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ postid: postid }),
  })
    .then((response) => {
      if (response.status === 401) {
        window.location.href = "../login/login.php?error=Like";
        return;
      }
      return response.json();
    })
    .then((date) => {
      if (!date || !date.success) {
        likeIcon.classList.toggle("liked");
        likeCount.textContent = date?.liked ? count - 1 : count + 1;
      }
    })
    .catch((error) => {
      console.error("Erro no fetch:", error);
      likeIcon.classList.toggle("liked");
      likeCount.textContent = count;
    });
}

//Função que formata datas para mostrar "Hoje", "Ontem" ou data normal
function datetime(dateStr, timeStr) {
  const segment = dateStr.split("/");
  if (segment.length !== 3) return dateStr + " às " + timeStr.slice(0, 5);

  const dateObj = new Date(segment[2], segment[1] - 1, segment[0]);

  const today = new Date();
  today.setHours(0, 0, 0, 0);
  const yesterday = new Date(today);
  yesterday.setDate(today.getDate() - 1);

  if (dateObj.getTime() === today.getTime()) {
    return `Hoje às ${timeStr.slice(0, 5)}`;
  } else if (dateObj.getTime() === yesterday.getTime()) {
    return `Ontem às ${timeStr.slice(0, 5)}`;
  } else {
    return `${dateStr} às ${timeStr.slice(0, 5)}`;
  }
}

//Função para carregar posts do servidor, paginados e com filtro
function loadPosts() {
  fetch(
    `../../php/loadposts.php?offset=${offset}&limit=${limit}&filter=${currentFilter}`
  )
    .then((response) => response.json())
    .then((posts) => {
      if (posts.length === 0 && offset === 0) {
        feed.innerHTML = "<p>Nenhum post encontrado.</p>";
        document.getElementById("btn-see-more").style.display = "none";
        return;
      } else if (posts.length === 0) {
        document.getElementById("btn-see-more").style.display = "none";
        return;
      }

      posts.forEach((post) => { //Para cada post recebido, cria o HTML e adiciona ao feed
        const container = document.createElement("div");
        container.className = "post-container";
        //Ajusta nome científico, mostrando vazio se não identificado
        let scientificName;
        if (post.especie == "Não identificado") {
          scientificName = "<br>";
        } else {
          scientificName = "(" + post.especie + ")";
        }

        //Define a classe "liked" se o post já estiver curtido pelo usuário
        const likeClass = post.liked ? "liked" : "";
        const datetimePub = datetime(
          post.data_publicacao,
          post.hora_publicacao
        );
        //Formata as datas de publicação e observação
        const datetimeObs = datetime(
          post.data_observacao,
          post.hora_observacao
        );

        //Insere badge de espécie invasora
        let invasorBadge = "";
        if (post.id_categoria == 3) {
          invasorBadge = `<span class="badge-invasive"><i class="fa-solid fa-triangle-exclamation"></i> Espécie invasora</span>`;
        }

        //Monta o conteúdo HTML
        container.innerHTML = ` 
                
                    <span id="datetime">${datetimePub}</span>
                    <div class="image-post">
                        <img src="${post.url_imagem}" alt="Imagem de ${post.nome_popular}" draggable="false" onclick="window.location.href = '../postdetails/postdetails.php?id=${post.id}'">
                    </div>
                    <div class="text-post">
                        <div class="flexname">
                            <span class="common-name">${post.nome_popular}</span>
                            <span class="specie">${scientificName}</span>
                        </div>
                        ${invasorBadge}                    
                        <div class="description"  onclick="window.location.href = '../postdetails/postdetails.php?id=${post.id}'">
                            <span class="description">${post.descricao}</span>
                        </div>

                        <div class="authoradress">
                            <div class="adress">
                                <i class="fa-solid fa-location-dot"></i>
                                <span>${post.nome_lugar}</span>
                            </div>
                            <div class="author">   
                                <i class="fa-solid fa-camera"></i>          
                                <span style="display: inline;">Por <a href="../profile/profile.php?username=${post.nome_usuario}" id="username">${post.nome_usuario}</a></span>
                            </div>
                            <div class="author">   
                                <i class="fa-solid fa-calendar"></i>       
                                <span>${datetimeObs}</span>
                            </div>
                        </div>

                        <div class="post-interactions">
                            <i class="fa-solid fa-comments comments" onclick="window.location.href = '../postdetails/postdetails.php?id=${post.id}'"></i><span>${post.qtde_coment}</span>
                            <i class="fa-solid fa-heart like ${likeClass}" id="${post.id}" onclick="like(this.id)"></i>
                            <span id="likes-${post.id}">${post.qtde_likes}</span>
                        </div>
                    </div>
                `;
        feed.appendChild(container);
      });

      offset += limit; //Atualiza o offset para próxima página

      //Se retornou menos posts do que o limite, esconde botão "ver mais"
      if (posts.length < limit) {
        document.getElementById("btn-see-more").style.display = "none";
      }
    })
    .catch((error) => {
      console.error("Erro ao carregar posts:", error);
    });
}

//Função que aplica um filtro novo para os posts e recarrega o feed
function applyFilter(filter) {
  if (currentFilter === filter) return;

  currentFilter = filter;
  offset = 0;
  feed.innerHTML = "";
  document.getElementById("btn-see-more").style.display = "flex";
  loadPosts();
}

//Para cada botão de filtro adiciona um evento de clique
const btnsFilter = document.querySelectorAll('#filter button');
btnsFilter.forEach(button => {
  button.addEventListener('click', () => {
    btnsFilter.forEach(b => b.classList.remove('selectedFilter'));
    button.classList.add('selectedFilter');
  });
});

//Voltar ao topo
const backToTopButton = document.getElementById("backToTop");
window.addEventListener("scroll", () => {
  if (window.scrollY > 300) {
    backToTopButton.style.display = "block";
  } else {
    backToTopButton.style.display = "none";
  }
});
backToTopButton.addEventListener("click", () => {
  window.scrollTo({
    top: 0,
    behavior: "smooth"
  });
});

document.getElementById("btn-see-more").addEventListener("click", loadPosts);
window.addEventListener("DOMContentLoaded", loadPosts);
