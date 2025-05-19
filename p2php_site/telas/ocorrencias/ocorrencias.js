const caixa = document.getElementById('caixa');
const btnVerMais = document.getElementById('ver-mais');

let ocorrenciasCarregadas = 0;
const ocorrenciasPorPagina = 3;
let todasocorrencias = [];

fetch('../../php_funcoes/buscarocorrencias.php')
    .then(response => response.json())
    .then(ocorrencias => {
        todasocorrencias = ocorrencias;
        caixa.innerHTML = '';  // limpa antes
        carregarocorrencias();    // carrega os primeiros 12
    })
    .catch(error => console.error('Erro ao carregar ocorrências', error));

btnVerMais.addEventListener('click', carregarocorrencias);

function carregarocorrencias() {
    const limite = ocorrenciasCarregadas + ocorrenciasPorPagina;
    const ocorrenciasParaMostrar = todasocorrencias.slice(ocorrenciasCarregadas, limite);

    ocorrenciasParaMostrar.forEach(ocorrencia => {
        let imgocorrenciaDiv = document.createElement('div');
        imgocorrenciaDiv.className = 'img-ocorrencia';
        imgocorrenciaDiv.style.backgroundImage = `url(${ocorrencia.img_url})`;
        imgocorrenciaDiv.style.backgroundSize = 'cover';
        imgocorrenciaDiv.style.backgroundPosition = 'center';

        if (ocorrencia.sensivel) {
            const fundoBlur = document.createElement('div');
            fundoBlur.className = 'fundo-blur';
            imgocorrenciaDiv.appendChild(fundoBlur);

            const olhoAberto = document.createElement('i');
            olhoAberto.className = 'fa-solid fa-eye olho-icone olho-aberto';

            const olhoFechado = document.createElement('i');
            olhoFechado.className = 'fa-solid fa-eye-slash olho-icone';

            olhoFechado.style.display = 'none';
            fundoBlur.style.display = 'block';

            imgocorrenciaDiv.appendChild(olhoAberto);
            imgocorrenciaDiv.appendChild(olhoFechado);

            olhoAberto.addEventListener('click', () => {
                fundoBlur.style.display = 'none';
                olhoAberto.style.display = 'none';
                olhoFechado.style.display = 'inline';
            });

            olhoFechado.addEventListener('click', () => {
                fundoBlur.style.display = 'block';
                olhoFechado.style.display = 'none';
                olhoAberto.style.display = 'inline';
            });
        }

        const titulo = document.createElement('h2');
        titulo.className = 'titulo-ocorrencia';
        titulo.textContent = ocorrencia.titulo;
        imgocorrenciaDiv.appendChild(titulo);

        const descDiv = document.createElement('div');
        descDiv.className = 'desc-ocorrencia';

        const descP = document.createElement('p');
        descP.className = 'desc';
        descP.textContent = ocorrencia.descricao;
        descDiv.appendChild(descP);

        const dataFormatada = new Date(ocorrencia.data).toLocaleDateString('pt-BR');
        const horaFormatada = ocorrencia.hora.slice(0, 5);
        const autorP = document.createElement('p');
        autorP.className = 'autor-ocorrencia';
        autorP.textContent = `Por ${ocorrencia.autor} em ${dataFormatada} às ${horaFormatada}`;
        descDiv.appendChild(autorP);

        const ocorrenciaDiv = document.createElement('div');
        ocorrenciaDiv.className = 'ocorrencia';
        ocorrenciaDiv.appendChild(imgocorrenciaDiv);
        ocorrenciaDiv.appendChild(descDiv);

        caixa.appendChild(ocorrenciaDiv);
    });

    ocorrenciasCarregadas += ocorrenciasParaMostrar.length;

    if (ocorrenciasCarregadas >= todasocorrencias.length) {
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