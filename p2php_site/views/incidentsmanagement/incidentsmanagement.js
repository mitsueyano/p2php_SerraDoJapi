function buildModal(action, fun, incidentId) {
    const modalHTML = `
      <div class="modal">
          <div class="modal-top">
              <i onclick="killModal()" id="modal-close" class="fa-solid fa-xmark"></i>
          </div>
          <div class="modal-body">
              <p>Deseja mesmo ${action} a ocorrência?</p>
          </div>
          <div class="modal-bottom">
              <button onclick="killModal()" id="modal-btn-cancel">Cancelar</button>
              <button onclick="${fun}(${incidentId})"id="modal-btn-yes"><span id="modal-btn-yes-text">Sim</span></button>
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

function killModal() {
    document.body.classList.remove('scroll-lock')
    document.querySelector('.modal-container').remove()
}

function approve(incidentId) {
    buildModal("aprovar", "approveIncident", incidentId)
}

function reject(incidentId) {
    buildModal("rejeitar", "rejectIncident", incidentId)
}

function approveIncident(incidentId) {
    
    const body = {
        id_ocorrencia: incidentId
    };

    // Configurações do fetch
    const options = {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(body)
    };

    // Fazendo a requisição
    fetch("../../php/approveIncident.php", options)
        .then(response => {
            // Verifica se a resposta foi bem sucedida (status 2xx)
            if (!response.ok) {
                // Se a resposta não estiver OK, rejeita a promessa com o status
                return response.json().then(err => Promise.reject(err));
            }
            return response.json();
        })
        .then(data => {
            // Tratamento da resposta bem sucedida
            console.log('Sucesso:', data);
            
            // Atualiza a UI conforme necessário
            if (data.success) {
                document.querySelector(`#card${incidentId}`).remove();
            } else {
                alert(`Erro: ${data.mensagem}`);
            }
            killModal()
        })
        .catch(error => {
            // Tratamento de erros de rede ou da API
            console.error('Erro:', error);
            
            if (error.mensagem) {
                // Erro retornado pela API
                alert(`Falha ao aprovar: ${error.mensagem}`);
            } else if (error instanceof TypeError) {
                // Erro de rede (falha na conexão)
                alert('Falha na conexão. Verifique sua internet e tente novamente.');
            } else {
                // Outros erros
                alert('Ocorreu um erro inesperado. Tente novamente mais tarde.');
            }
            killModal()
        });
}

function rejectIncident(incidentId) {
    fetch(`../../php/rejectIncident.php/${incidentId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
        }
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => Promise.reject(err));
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            document.querySelector(`#card${incidentId}`).remove();
        } else {
            alert(`Erro: ${data.mensagem}`);
        }
        killModal()
    })
    .catch(error => {
        console.error('Erro:', error);
        alert(error.mensagem || 'Erro ao excluir ocorrência');
        killModal()
    });
}

function loadIncidents() {
    fetch('../../php/loadpendingincidents.php')
        .then(response => response.json())
        .then(incidents => {
            console.log(incidents)
            incidents.forEach(i => {
                const card = document.createElement('div')
                card.classList.add('incident')
                card.innerHTML = card.innerHTML + `
                <div class="card-image">
                    <img src="${i.img_url}" alt="">
                </div>

                <div class="card-content">
                    <div class="card-header">
                        <h2 class="card-title">${i.titulo}</h2>
                        <span class="card-date">${i.data} às ${i.hora.slice(0,5)}</span>
                    </div>

                    <p class="card-description">${i.descricao}</p>

                    <div class="card-meta">
                        <div class="meta-item">
                            <i class="fa-solid fa-location-dot"></i> ${i.nome_lugar}
                        </div>
                        <div class="meta-item">
                            <i class="fa-solid fa-user"></i> Publicado por ${i.autor}
                        </div>
                        <div class="sensitive-tag ${i.sensivel ? "" : "hidden"}"><span>Conteúdo Sensível <i
                                    class="fa-solid fa-triangle-exclamation"></i></span></div>
                    </div>
                    <div class="approval-buttons">
                        <button class="btn btn-reject" onclick="reject(${i.id})">Rejeitar</button>
                        <button class="btn btn-approve" onclick="approve(${i.id})">Aprovar</button>
                    </div>
                </div>`
                card.id = "card" + i.id
                document.querySelector(".incidents-container").appendChild(card)
            });
        })


}

window.addEventListener('load', () => {
    loadIncidents();
    const header = document.getElementById("header");
    window.scrollTo({
        top: header.offsetHeight,
    });
});