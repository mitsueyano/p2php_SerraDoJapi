// Função para formatar a data
function formatDateTime(dateStr, timeStr) {
  const date = new Date(dateStr.split("/").reverse().join("-") + "T" + timeStr);
  const options = {
    day: "2-digit",
    month: "2-digit",
    year: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  };
  return date.toLocaleString("pt-BR", options);
}

// Função para carregar os dados do post
function loadPostData() {
  const postId = new URLSearchParams(window.location.search).get("id");
  if (!postId) {
    document.getElementById("post-container").innerHTML =
      '<p class="error">ID do registro não fornecido</p>';
    return;
  }

  fetch(`../../php/loadpostinfo.php?id=${postId}`)
    .then((response) => {
      if (!response.ok) {
        throw new Error("Erro na requisição");
      }
      return response.json();
    })
    .then((data) => {
      if (!data.success) {
        throw new Error(data.message || "Erro ao carregar post");
      }
      renderPost(data.data);
    })
    .catch((error) => {
      console.error("Erro:", error);
      document.getElementById("post-container").innerHTML = `
                <div class="error-message">
                    <i class="fas fa-exclamation-triangle"></i>
                    <h2>Erro ao carregar post</h2>
                    <p>${error.message}</p>
                </div>
            `;
    });
}

// Função para renderizar o post
function renderPost(postData) {
  const postContainer = document.getElementById("post-container");

  // Construir HTML do post
  let html = `
        <div class="post-detail">
            <div class="post-header" onclick="window.location.href = '../profile/profile.php?username=${postData.usuario.username}'">
                <div class="author-info">
                    <img src="${
                      postData.usuario.imagem_perfil ||
                      "../img/default-profile.png"
                    }" alt="Foto de perfil" class="profile-pic">
                    <div>
                        <h2>${postData.usuario.nome} ${
    postData.usuario.sobrenome
  }</h2>
                        <span class="username">@${
                          postData.usuario.username
                        }</span>
                        ${
                          postData.usuario.nivel_acesso === "especialista"
                            ? '<span class="badge">Especialista</span>'
                            : ""
                        }
                    </div>
                </div>
                <div class="post-meta">
                    <span class="post-date">${formatDateTime(
                      postData.data_publicacao,
                      postData.hora_publicacao
                    ).replace(",", " às")}</span>
                </div>
            </div>

            <div class="post-content">
                <div class="post-image">
                    ${
                      postData.imagem_url
                        ? `<img src="${postData.imagem_url}" alt="${postData.taxonomia.nome_popular}">`
                        : '<div class="no-image"><i class="fas fa-camera"></i><span>Sem imagem</span></div>'
                    }
                </div>

                <div class="post-info">
                    <h1 class="species-name">${
                      postData.taxonomia.nome_popular
                    }</h1>
                    <p class="scientific-name">${postData.taxonomia.especie}</p>
                    
                    <div class="taxonomy-info">
                        <div><span class="label">Classe:</span> ${
                          postData.taxonomia.classe
                        }</div>
                        <div><span class="label">Ordem:</span> ${
                          postData.taxonomia.ordem
                        }</div>
                        <div><span class="label">Família:</span> ${
                          postData.taxonomia.familia
                        }</div>
                        <div><span class="label">Categoria:</span> ${
                          postData.taxonomia.categoria
                        }</div>
                    </div>

                    <div class="location-info">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>${postData.geolocalizacao.nome_lugar}</span>
                    </div>

                    <div class="observation-info">
                        <p><strong>Data da observação:</strong> ${formatDateTime(
                          postData.data_observacao,
                          postData.hora_observacao
                        )}</p>
                        ${
                          postData.identificacao
                            ? '<span class="tag identified">Verificado por especialista <i class="fa-solid fa-check"></i></span>'
                            : ""
                        }
                        ${
                          postData.especie_invasora
                            ? '<span class="tag invasive">Espécie invasora</span>'
                            : ""
                        }
                    </div>

                    <div class="post-description">
                        <p>${postData.descricao}</p>
                    </div>
                </div>

                <div class="post-interactions">
                    <button class="like-btn ${
                      postData.liked ? "liked" : ""
                    }" onclick="toggleLike(${postData.id})">
                        <i class="${
                          postData.liked ? "fas" : "far"
                        } fa-heart"></i>
                        <span id="like-count">${postData.likes}</span>
                    </button>
                    <div class="comments-count">
                        <i class="fas fa-comment"></i>
                        <span>${postData.comentarios_count} comentários</span>
                    </div>
                </div>
            </div>

            <div class="comments-section">
                <h3>Comentários</h3>
                <div class="comment-form">
                    <textarea id="comment-input" placeholder="Adicione um comentário..."></textarea>
                    <button onclick="postComment(${
                      postData.id
                    })">Enviar</button>
                </div>
                <div id="comments-container">
                    ${renderComments(postData.comentarios)}
                </div>
            </div>
        </div>
    `;

  postContainer.innerHTML = html;
}

function renderComments(comments) {
  return comments
    .map(
      (comment) => `
        <div class="comment ${comment.respostas.length ? "has-replies" : ""}">
            <div class="comment-header">
                <img src="${
                  comment.usuario.imagem_perfil || "../img/default-profile.png"
                }" alt="Foto de perfil" class="comment-profile-pic" data-username="${
                  comment.usuario.username
                }">
                <div>
                    <span class="comment-author" data-username="${
                      comment.usuario.username
                    }">${comment.usuario.nome} ${comment.usuario.sobrenome}</span>
                    <span class="comment-username" data-username="${
                      comment.usuario.username
                    }">@${comment.usuario.username}</span>
                    ${
                      comment.usuario.nivel_acesso == "especialista"
                        ? '<span class="badge">Especialista</span>'
                        : ""
                    }
                </div>
                <span class="comment-date">${new Date(comment.data_publicacao)
                  .toLocaleString("pt-BR", {
                    day: "2-digit",
                    month: "2-digit",
                    year: "numeric",
                    hour: "2-digit",
                    minute: "2-digit",
                  })
                  .replace(",", " às")}</span>
            </div>
            <div class="comment-content">
                <p>${comment.conteudo}</p>
            </div>
            <div class="comment-actions">
                <button class="reply-btn" onclick="showReplyForm(${
                  comment.id
                })">Responder</button>
            </div>
            
            <div class="reply-form-container" id="reply-form-${
              comment.id
            }" style="display: none;">
                <textarea id="reply-input-${
                  comment.id
                }" placeholder="Escreva sua resposta..."></textarea>
                <button onclick="postReply(${comment.id}, ${
        comment.id_registro
      })">Enviar resposta</button>
            </div>
            
            ${
              comment.respostas.length
                ? `
                <div class="replies">
                    ${comment.respostas
                      .map(
                        (reply) => `
                        <div class="comment reply">
                            <div class="comment-header">
                                <img src="${
                                  reply.usuario.imagem_perfil ||
                                  "../img/default-profile.png"
                                }" alt="Foto de perfil" class="comment-profile-pic" data-username="${
                                  reply.usuario.username
                                }">
                                <div>
                                    <span class="comment-author" data-username="${
                                      reply.usuario.username
                                    }">${reply.usuario.nome} ${
                          reply.usuario.sobrenome
                        }</span>
                                    <span class="comment-username" data-username="${
                                      reply.usuario.username
                                    }">@${reply.usuario.username}</span>
                                    ${
                                      reply.usuario.nivel_acesso ==
                                      "especialista"
                                        ? '<span class="badge">Especialista</span>'
                                        : ""
                                    }
                                </div>
                                <span class="comment-date">${new Date(
                                  reply.data_publicacao
                                )
                                  .toLocaleString("pt-BR", {
                                    day: "2-digit",
                                    month: "2-digit",
                                    year: "numeric",
                                    hour: "2-digit",
                                    minute: "2-digit",
                                  })
                                  .replace(",", " às")}</span>
                            </div>
                            <div class="comment-content">
                                <p>${reply.conteudo}</p>
                            </div>
                        </div>
                    `
                      )
                      .join("")}
                </div>
            `
                : ""
            }
        </div>
    `
    )
    .join("");
}
document.addEventListener("click", (event) => {
  const el = event.target;

  if (
    (el.classList.contains("comment-profile-pic") ||
      el.classList.contains("comment-author") ||
      el.classList.contains("comment-username")) &&
    el.dataset.username
  ) {
    window.location.href = `../profile/profile.php?username=${el.dataset.username}`;
  }
});



function toggleLike(postId) {
  fetch("../../php/likeupdate.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ postid: postId }),
  })
    .then((response) => {
      if (response.status === 401) {
        window.location.href = "../login/login.php?error=Like";
        return;
      }
      return response.json();
    })
    .then((data) => {
      if (data && data.success) {
        const likeBtn = document.querySelector(".like-btn");
        const likeIcon = likeBtn.querySelector("i");
        const likeCount = document.getElementById("like-count");

        likeBtn.classList.toggle("liked");
        if (likeBtn.classList.contains("liked")) {
          likeIcon.classList.replace("far", "fas");
          likeCount.textContent = parseInt(likeCount.textContent) + 1;
        } else {
          likeIcon.classList.replace("fas", "far");
          likeCount.textContent = parseInt(likeCount.textContent) - 1;
        }
      }
    })
    .catch((error) => {
      console.error("Erro ao atualizar like:", error);
    });
}

function postComment(postId) {
  const commentInput = document.getElementById("comment-input");
  const commentText = commentInput.value.trim();

  if (!commentText) {
    alert("Por favor, digite um comentário antes de enviar.");
    return;
  }

  fetch("../../php/postcomment.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      postid: postId,
      content: commentText,
    }),
  })
    .then(async (response) => {
      if (response.status === 401) {
        window.location.href = "../login/login.php?error=Comment";
        return;
      }

      const responseData = await response.json();

      if (!response.ok) {
        throw new Error(responseData.message || "Erro ao postar comentário");
      }

      return responseData;
    })
    .then((data) => {
      if (data && data.success) {
        // Criar o novo comentário diretamente no DOM
        const commentsContainer = document.getElementById("comments-container");
        const newComment = document.createElement("div");
        newComment.className = "comment";

        // Formatar a data atual
        const currentDate = new Date()
          .toLocaleString("pt-BR", {
            day: "2-digit",
            month: "2-digit",
            year: "numeric",
            hour: "2-digit",
            minute: "2-digit",
          })
          .replace(",", " às");
       newComment.innerHTML = `
    <div class="comment-header">
        <img src="${data.userData.pfp}" alt="Foto de perfil" class="comment-profile-pic" data-username="${data.userData.username}">
        <div>
            <span class="comment-author" data-username="${data.userData.username}">${data.userData.name} ${data.userData.lastname}</span>
            <span class="comment-username" data-username="${data.userData.username}">@${data.userData.username}</span>
            ${
              data.userData.isSpecialist
                ? '<span class="badge">Especialista</span>'
                : ""
            }
        </div>
        <span class="comment-date">${currentDate}</span>
    </div>
    <div class="comment-content">
        <p>${commentText}</p>
    </div>
    <div class="comment-actions">
        <button class="reply-btn" onclick="showReplyForm(${data.commentId})">Responder</button>
    </div>
    <div class="reply-form-container" id="reply-form-${data.commentId}" style="display: none;">
        <textarea id="reply-input-${data.commentId}" placeholder="Escreva sua resposta..."></textarea>
        <button onclick="postReply(${data.commentId}, ${postId})">Enviar resposta</button>
    </div>
`;


        // Adicionar o novo comentário no topo da lista
        commentsContainer.prepend(newComment);

        // Limpar o campo de comentário
        commentInput.value = "";

        // Atualizar contador de comentários
        const commentsCount = document.querySelector(".comments-count span");
        if (commentsCount) {
          const currentCount = parseInt(commentsCount.textContent) || 0;
          commentsCount.textContent = currentCount + 1 + " comentários";
        }
      } else {
        throw new Error(data.message || "Erro ao processar comentário");
      }
    })
    .catch((error) => {
      console.error("Erro ao postar comentário:", error);
      alert(error.message);
    });
}

function showReplyForm(commentId) {
  const form = document.getElementById(`reply-form-${commentId}`);
  form.style.display = form.style.display === "none" ? "block" : "none";
}

function postReply(commentId, postId) {
  const replyInput = document.getElementById(`reply-input-${commentId}`);
  const replyText = replyInput.value.trim();

  if (!replyText) {
    alert("Por favor, digite uma resposta antes de enviar.");
    return;
  }

  fetch("../../php/postcomment.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      Accept: "application/json",
    },
    body: JSON.stringify({
      postid: parseInt(postId),
      content: replyText,
      parentid: parseInt(commentId),
    }),
  })
    .then((response) => {
      // Primeiro verifica o status da resposta
      if (response.status === 401) {
        window.location.href = "../login/login.php?error=Comment";
        return;
      }

      // Depois verifica se é JSON válido
      return response.json().then((data) => {
        if (!response.ok) {
          throw new Error(data.message || `Erro ${response.status}`);
        }
        return data;
      });
    })
    .then((data) => {
      if (!data || !data.success) {
        throw new Error(data?.message || "Resposta inválida do servidor");
      }

      // Encontrar o comentário pai
      const parentComment = document
        .querySelector(`#reply-form-${commentId}`)
        .closest(".comment");

      // Criar elemento da resposta
      const replyElement = document.createElement("div");
      replyElement.className = "comment reply";

      // Formatar a data atual
      const currentDate = new Date()
        .toLocaleString("pt-BR", {
          day: "2-digit",
          month: "2-digit",
          year: "numeric",
          hour: "2-digit",
          minute: "2-digit",
        })
        .replace(",", " às");

      replyElement.innerHTML = `
            <div class="comment-header">
                <img src="${
                  data.userData.pfp || "../img/default-profile.png"
                }" alt="Foto de perfil" class="comment-profile-pic">
                <div>
                    <span class="comment-author">${data.userData.name}</span>
                    <span class="comment-username">@${
                      data.userData.username
                    }</span>
                    ${
                      data.userData.isSpecialist
                        ? '<span class="badge">Especialista</span>'
                        : ""
                    }
                </div>
                <span class="comment-date">${currentDate}</span>
            </div>
            <div class="comment-content">
                <p>${replyText}</p>
            </div>
        `;

      // Adicionar a resposta
      let repliesContainer = parentComment.querySelector(".replies");
      if (!repliesContainer) {
        repliesContainer = document.createElement("div");
        repliesContainer.className = "replies";
        parentComment.appendChild(repliesContainer);
        parentComment.classList.add("has-replies");
      }
      repliesContainer.prepend(replyElement);

      // Limpar e esconder o formulário
      replyInput.value = "";
      document.getElementById(`reply-form-${commentId}`).style.display = "none";

      // Atualizar contador de comentários
      updateCommentCount(1);
    })
    .catch((error) => {
      console.error("Erro ao postar resposta:", error);

      // Se for uma mensagem de sucesso que veio como erro, trata diferente
      if (error.message.includes('{"success":true')) {
        try {
          const data = JSON.parse(error.message);
          if (data.success) {
            // Se realmente foi um sucesso, apenas logamos
            console.log("Resposta postada com sucesso:", data);
            return;
          }
        } catch (e) {
          // Não é JSON válido, mostra o erro original
          alert("Erro ao postar resposta: " + error.message);
        }
      } else {
        alert("Erro ao postar resposta: " + error.message);
      }
    });
}

// Função auxiliar para atualizar o contador
function updateCommentCount(increment = 1) {
  const commentsCount = document.querySelector(".comments-count span");
  if (commentsCount) {
    const currentCount = parseInt(commentsCount.textContent) || 0;
    commentsCount.textContent = currentCount + increment + " comentários";
  }
}

window.addEventListener('load', () => {
    const header = document.getElementById("header");
    window.scrollTo({
        top: header.offsetHeight,
        behavior: "smooth"
    });
});

// Carregar os dados quando a página estiver pronta
document.addEventListener("DOMContentLoaded", loadPostData);
