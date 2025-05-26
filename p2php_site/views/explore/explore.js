let offset = 0;
let currentFilter = "recentes";

const limit = 12;
const feed = document.getElementById("feed");

function like(postid) {
  const likeIcon = document.getElementById(postid);
  const likeCount = document.getElementById(`likes-${postid}`);

  likeIcon.classList.toggle("liked");
  let count = parseInt(likeCount.textContent);
  likeCount.textContent = likeIcon.classList.contains("liked")
    ? count + 1
    : count - 1;

  fetch("../../php/likeupdate.php", {
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

function openModal(urlImagem) {
  const modal = document.getElementById("modal");
  const img = document.getElementById("imgModal");
  img.src = urlImagem;
  modal.style.display = "block";
}

function closeModal() {
  document.getElementById("modal").style.display = "none";
}

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

function loadPosts() {
  fetch(
    `../../php/loadposts.php?offset=${offset}&limit=${limit}&filter=${currentFilter}`
  )
    .then((response) => response.json())
    .then((posts) => {
        console.log(posts)
      if (posts.length === 0 && offset === 0) {
        feed.innerHTML = "<p>Nenhum post encontrado.</p>";
        document.getElementById("btn-see-more").style.display = "none";
        return;
      } else if (posts.length === 0) {
        document.getElementById("btn-see-more").style.display = "none";
        return;
      }

      posts.forEach((post) => {
        const container = document.createElement("div");
        container.className = "post-container";
        if (post.especie == "Não identificado") {
          scientificName = "<br>";
        } else {
          scientificName = "(" + post.especie + ")";
        }

        const likeClass = post.liked ? "liked" : "";
        const datetimePub = datetime(
          post.data_publicacao,
          post.hora_publicacao
        );
        const datetimeObs = datetime(
          post.data_observacao,
          post.hora_observacao
        );

        container.innerHTML = ` 
                
                    <span id="datetime">${datetimePub}</span>
                    <div class="image-post">
                        <img src="${post.url_imagem}" alt="Imagem de ${post.nome_popular}" draggable="false" onclick="openModal('${post.url_imagem}')">
                    </div>
                    <div class="text-post">
                        <div class="flexname">
                            <span class="common-name">${post.nome_popular}</span>
                            <span class="specie">${scientificName}</span>
                        </div>                        
                        <div class="description">
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
                            <i class="fa-solid fa-comments comments"></i><span>${post.qtde_coment}</span>
                            <i class="fa-solid fa-heart like ${likeClass}" id="${post.id}" onclick="like(this.id)"></i>
                            <span id="likes-${post.id}">${post.qtde_likes}</span>
                        </div>
                    </div>
                `;

        feed.appendChild(container);
      });

      offset += limit;

      if (posts.length < limit) {
        document.getElementById("btn-see-more").style.display = "none";
      }
    })
    .catch((error) => {
      console.error("Erro ao carregar posts:", error);
    });
}

function applyFilter(filter) {
  if (currentFilter === filter) return;

  currentFilter = filter;
  offset = 0;
  feed.innerHTML = "";
  document.getElementById("btn-see-more").style.display = "flex";
  loadPosts();
}

document.getElementById("btn-see-more").addEventListener("click", loadPosts);

window.addEventListener("DOMContentLoaded", loadPosts);
