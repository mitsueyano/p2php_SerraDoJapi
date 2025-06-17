const box = document.getElementById('box');
const btnVerMais = document.getElementById('btn-see-more');

//Controla quantas ocorrências já foram carregados e quantos carregar por vez
let incidentsLoaded = 0;
const incidentsPerPage = 8;
let allIncidents = [];

fetch('../../php/loadincidents.php')
    .then(response => response.json())
    .then(incidents => {
        allIncidents = incidents;
        box.innerHTML = '';
        loadincidents();
    })
    .catch(error => console.error('Erro ao carregar ocorrências', error));

btnVerMais.addEventListener('click', loadincidents);

//Função que carrega um grupo de incidentes e os adiciona ao container
function loadincidents() {
    const limite = incidentsLoaded + incidentsPerPage;
    const incidentsToShow = allIncidents.slice(incidentsLoaded, limite);

    incidentsToShow.forEach(incident => { //Para cada ocorrência a ser exibido, cria elementos e configura estilos e conteúdo
        let imgincidentDiv = document.createElement('div');
        imgincidentDiv.className = 'img-incident';
        imgincidentDiv.style.backgroundImage = `url(${incident.img_url})`;
        imgincidentDiv.style.backgroundSize = 'cover';
        imgincidentDiv.style.backgroundPosition = 'center';

        if (incident.sensivel) {  //Se a ocorrência for marcado como sensível, adiciona um efeito de blur e ícones para mostrar/esconder
            const blur = document.createElement('div');
            blur.className = 'blur';
            imgincidentDiv.appendChild(blur);

            const open = document.createElement('i');
            open.className = 'fa-solid fa-eye eye-icon eye-open';

            const closed = document.createElement('i');
            closed.className = 'fa-solid fa-eye-slash eye-icon';

            //Inicialmente, o blur está visível e o ícone de olho fechado escondido
            closed.style.display = 'none';
            blur.style.display = 'block';

            imgincidentDiv.appendChild(open);
            imgincidentDiv.appendChild(closed);

            //Evento para clicar no ícone "olho aberto" e revelar a imagem (remover blur)
            open.addEventListener('click', () => {
                blur.style.display = 'none';
                open.style.display = 'none';
                closed.style.display = 'inline';
            });

            //Evento para clicar no ícone "olho fechado" e esconder a imagem (mostrar blur)
            closed.addEventListener('click', () => {
                blur.style.display = 'block';
                closed.style.display = 'none';
                open.style.display = 'inline';
            });
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

        //Formata a data e hora
        const date = new Date(incident.data).toLocaleDateString('pt-BR');
        const time = incident.hora.slice(0, 5);
        const authorP = document.createElement('p');
        authorP.className = 'incident-author';
        authorP.textContent = `Por ${incident.autor} em ${date} às ${time}`;
        descDiv.appendChild(authorP);

        const incidentDiv = document.createElement('div');
        incidentDiv.className = 'incident';
        incidentDiv.appendChild(imgincidentDiv);
        incidentDiv.appendChild(descDiv);

        box.appendChild(incidentDiv);
    });

    incidentsLoaded += incidentsToShow.length; //Atualiza o contador de incidentes carregados 

    if (incidentsLoaded >= allIncidents.length) {
        btnVerMais.style.display = 'none';
    }
}

window.addEventListener('load', () => {
    const header = document.getElementById("header");
    window.scrollTo({
        top: header.offsetHeight,
        behavior: "smooth"
    });
});

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
