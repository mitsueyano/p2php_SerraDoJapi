const box = document.getElementById('box');
const btnVerMais = document.getElementById('btn-see-more');

let incidentsLoaded = 0;
const incidentsPerPage = 4;
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

function loadincidents() {
    const limite = incidentsLoaded + incidentsPerPage;
    const incidentsToShow = allIncidents.slice(incidentsLoaded, limite);

    incidentsToShow.forEach(incident => {
        let imgincidentDiv = document.createElement('div');
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
            blur.style.display = 'block';

            imgincidentDiv.appendChild(open);
            imgincidentDiv.appendChild(closed);

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

        const incidentDiv = document.createElement('div');
        incidentDiv.className = 'incident';
        incidentDiv.appendChild(imgincidentDiv);
        incidentDiv.appendChild(descDiv);

        box.appendChild(incidentDiv);
    });

    incidentsLoaded += incidentsToShow.length;

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

const checkbox = document.getElementById('identified');
const items = document.querySelectorAll('.items');