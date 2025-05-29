// Estado global para gerenciar os posts e likes
const postsState = {};

// Configurações de paginação
const limits = {
  registros: 9,
  ocorrencias: 6,
  curtidos: 9
};

let last_ids = {
  registros: 0,
  ocorrencias: 0,
  curtidos: 0
};

// Elementos da UI
const tabs = document.querySelectorAll(".tab");
const contents = document.querySelectorAll(".tab-content");
const username = new URLSearchParams(document.location.search).get("username") == null ? userData.username : new URLSearchParams(document.location.search).get("username");

// Inicialização
document.addEventListener('DOMContentLoaded', () => {
  // Eventos das abas
  tabs.forEach(tab => {
    tab.addEventListener("click", () => {
      tabs.forEach(t => t.classList.remove("active"));
      contents.forEach(c => c.classList.remove("active"));
      
      tab.classList.add("active");
      const tabName = tab.dataset.tab;
      document.getElementById(tabName).classList.add("active");
      carregarAba(tabName);
    });
  });

  // Carrega a aba inicial
  carregarAba("registros");

  // Evento delegado para likes
  document.addEventListener('click', (e) => {
    if (e.target.classList.contains('like')) {
      handleLikeClick(e.target.dataset.postid);
    }
  });

  // Evento dos botões "Ver mais"
  document.querySelectorAll('#btn-see-more').forEach(btn => {
    btn.addEventListener('click', function() {
      carregarMais(this.closest('.tab-content').id);
    });
  });
});

// Funções principais
function carregarAba(aba) {
  last_ids[aba] = 0;
  document.querySelector(`#${aba} .posts-list`).innerHTML = "";
  document.querySelector(`#${aba} #div-see-more`).style.display = "flex";
  carregarMais(aba);
}

function carregarMais(aba) {
  const url = `../../php/loaduser${aba === 'ocorrencias' ? 'incidents' : aba === 'curtidos' ? 'likeds' : 'posts'}.php?targetun=${username}&limit=${limits[aba]}&last_id=${last_ids[aba]}`;
  
  fetch(url)
    .then(res => res.json())
    .then(data => {
      if (data.registros?.length) {
        last_ids[aba] = data.last_id;
        renderAba(aba, data.registros);
        document.querySelector(`#${aba} #div-see-more`).style.display = data.has_more ? "flex" : "none";
      } else {
        document.querySelector(`#${aba} #div-see-more`).style.display = "none";
      }
    })
    .catch(console.error);
}

function renderAba(aba, posts) {
  const container = document.querySelector(`#${aba} .posts-list`);
  
  posts.forEach(post => {
    // Atualiza o estado global do post
    if (!postsState[post.id]) {
      postsState[post.id] = {
        liked: post.liked,
        qtde_likes: post.qtde_likes || 0,
        elements: []
      };
    }

    const element = aba === "ocorrencias" ? createIncidentElement(post) : createPostElement(post, post.id);
    container.appendChild(element);
    
    // Armazena referência ao elemento criado
    postsState[post.id].elements.push({
      element: element,
      tab: aba
    });
  });
}

function createPostElement(post, postId) {
  const card = document.createElement("div");
  card.className = "post-container";
  
  const state = postsState[postId] || { liked: false, qtde_likes: 0 };
  const scientificName = post.especie === "Não identificado" ? "<br>" : `(${post.especie})`;
  const likeClass = state.liked ? "liked" : "";

  const datetimePub = datetime(post.data_publicacao, post.hora_publicacao);
  const datetimeObs = datetime(post.data_observacao, post.hora_observacao);
  if (userData.own) card.innerHTML = `
    <div id="actions">
        <i id="removePost" onclick="buildModal({'type': 'o registro', 'name': '${post.nome_popular}', 'id': ${post.id}})" class="fa-solid fa-trash-can"></i>
        <i id="editPost" onclick="editPost(${post.id})" class="fa-solid fa-pen-to-square"></i>
    </div>
  `
  card.innerHTML = card.innerHTML + `
    <span id="datetime">${datetimePub}</span>
    <div class="image-post">
        <img src="${post.url_imagem}" alt="Imagem de ${post.nome_popular}" draggable="false" onclick="">
    </div>
    <div class="text-post">
        <div class="flexname">
            <span class="common-name">${post.nome_popular}</span>
            <span class="specie">${scientificName}</span>
        </div>                        
        <div class="description">
            <span class="description">${post.descricao || ""}</span>
        </div>

        <div class="authoradress">
            <div class="adress">
                <i class="fa-solid fa-location-dot"></i>
                <span>${post.nome_lugar || ""}</span>
            </div>
            <div class="author">   
                <i class="fa-solid fa-camera"></i>          
                <span style="display: inline;">Por <a href="#">${post.nome_usuario}</a></span>
            </div>
            <div class="author">   
                <i class="fa-solid fa-calendar"></i>       
                <span>${datetimeObs}</span>
            </div>
        </div>

        <div class="post-interactions">
            <i class="fa-solid fa-comments comments"></i><span>${post.qtde_coment || 0}</span>
            <i class="fa-solid fa-heart like ${likeClass}" data-postid="${postId}"></i>
            <span data-postid="${postId}-count">${state.qtde_likes}</span>
        </div>
    </div>
  `;
  card.id = "post" + postId
  return card;
}

function createIncidentElement(incident) {
  const incidentDiv = document.createElement("div");
  incidentDiv.className = "incident";

  const imgincidentDiv = document.createElement('div');
  imgincidentDiv.className = 'img-incident';
  imgincidentDiv.style.backgroundImage = `url(${incident.img_url})`;
  imgincidentDiv.style.backgroundSize = 'cover';
  imgincidentDiv.style.backgroundPosition = 'center';

  if (incident.sensivel) {
      const blur = document.createElement('div');
      blur.className = 'blur';
      imgincidentDiv.appendChild(blur);

      const open = document.createElement('i');
      open.className = 'fa-solid fa-eye eye-icon eye-open';

      const closed = document.createElement('i');
      closed.className = 'fa-solid fa-eye-slash eye-icon';
      closed.style.display = 'none';

      open.addEventListener('click', () => {
          blur.style.display = 'none';
          open.style.display = 'none';
          closed.style.display = 'inline';
      });

      closed.addEventListener('click', () => {
          blur.style.display = 'block';
          closed.style.display = 'none';
          open.style.display = 'inline';
      });

      imgincidentDiv.append(open, closed);
  }

  const title = document.createElement('span');
  title.className = 'title-incident';
  title.textContent = incident.titulo;
  imgincidentDiv.appendChild(title);

  const descDiv = document.createElement('div');
  descDiv.className = 'desc-incident';

  const descP = document.createElement('p');
  descP.className = 'desc';
  descP.textContent = incident.descricao;
  descDiv.appendChild(descP);

  const date = new Date(incident.data).toLocaleDateString('pt-BR');
  const time = incident.hora.slice(0, 5);
  const authorP = document.createElement('p');
  authorP.className = 'incident-author';
  authorP.textContent = `Por ${incident.autor} em ${date} às ${time}`;
  descDiv.appendChild(authorP);

  incidentDiv.append(imgincidentDiv, descDiv);
  return incidentDiv;
}

function handleLikeClick(postid) {
  const state = postsState[postid];
  if (!state) return;

  // Atualização otimista da UI
  const newLikeState = !state.liked;
  const newCount = newLikeState ? state.qtde_likes + 1 : state.qtde_likes - 1;

  // Atualiza todos os elementos do post em todas as abas
  state.elements.forEach(item => {
    const likeIcon = item.element.querySelector(`[data-postid="${postid}"]`);
    const countElement = item.element.querySelector(`[data-postid="${postid}-count"]`);
    
    if (likeIcon) likeIcon.classList.toggle("liked", newLikeState);
    if (countElement) countElement.textContent = newCount;
  });

  // Atualiza estado global
  postsState[postid] = {
    ...state,
    liked: newLikeState,
    qtde_likes: newCount
  };

  // Envia para o servidor
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
  .then((data) => {
    if (!data || !data.success) {
      // Reverte se houver erro
      updatePostState(postid, state.liked, state.qtde_likes);
    }
  })
  .catch((error) => {
    console.error("Erro no fetch:", error);
    // Reverte em caso de erro de rede
    updatePostState(postid, state.liked, state.qtde_likes);
  });
}

function updatePostState(postid, liked, count) {
  const state = postsState[postid];
  if (!state) return;

  // Atualiza todos os elementos do post
  state.elements.forEach(item => {
    const likeIcon = item.element.querySelector(`[data-postid="${postid}"]`);
    const countElement = item.element.querySelector(`[data-postid="${postid}-count"]`);
    
    if (likeIcon) likeIcon.classList.toggle("liked", liked);
    if (countElement) countElement.textContent = count;
  });

  // Atualiza estado global
  postsState[postid] = {
    ...state,
    liked: liked,
    qtde_likes: count
  };
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

function removePost(postId) {
    document.getElementById('modal-btn-yes-text').classList.add('hidden')
    document.getElementById('remove-post-loading').classList.remove('hidden')
    fetch(`../../php/deletepost.php/${postId}`, {
    method: 'DELETE',
    headers: {
      'Content-Type': 'application/json'
    }
  })
  .then(response => {
    if (!response.ok) {
      return response.json().then(err => Promise.reject(err));
    }
    return response.json();
  })
  .then(data => {
    console.log('Sucesso:', data);
    alert('Post excluído com sucesso!');
    // Aqui você pode remover o post da interface ou recarregar a lista
    killModal()
    while (document.getElementById("post" + postId) !== null) {
      document.getElementById("post" + postId).remove()
    }
  })
  .catch(error => {
    // Tratamento de erros
    console.error('Erro ao excluir post:', error);
    
    if (error.error) {
      // Erro vindo do backend (já parseado)
      alert(`Erro: ${error.error}`);
    } else if (error.message) {
      // Erro de rede ou outro erro do fetch
      alert(`Falha na requisição: ${error.message}`);
    } else {
      // Erro genérico
      alert('Ocorreu um erro ao excluir o post');
    }
    document.getElementById('modal-btn-yes-text').classList.add('hidden')
    document.getElementById('remove-post-loading').classList.remove('hidden')
  });
}

function editPost(postId) {
  alert("Em desenvolvimento")
}

function buildModal(info){
    const modalHTML = `
      <div class="modal">
          <div class="modal-top">
              <i onclick="killModal()" id="modal-close" class="fa-solid fa-xmark"></i>
          </div>
          <div class="modal-body">
              <p>Deseja mesmo excluir ${info.type} ${info.name}?</p>
          </div>
          <div class="modal-bottom">
              <button onclick="killModal()" id="modal-btn-cancel">Cancelar</button>
              <button onclick="removePost(${info.id})"id="modal-btn-yes"><span id="modal-btn-yes-text">Sim</span><div id="remove-post-loading" class="loading-spinner hidden"></div></button>
          </div>
      </div>
    `
    const modal = document.createElement("div")
    modal.classList.add('modal-container')
    modal.innerHTML = modalHTML
    modal.addEventListener("click", (ev) => {
      const modalContainer = document.querySelector('.modal-container');
      if (modalContainer && ev.target === modalContainer) {
          killModal();
      }
    })
    document.body.appendChild(modal)
    document.body.classList.add('scroll-lock')
}

function killModal(){
  document.body.classList.remove('scroll-lock')
  document.querySelector('.modal-container').remove()
}