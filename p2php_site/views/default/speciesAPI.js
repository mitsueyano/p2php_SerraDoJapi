//Seleciona os elementos do formulário para preencheimento
const inputCommonName = document.getElementById("common-name");
const inputScientificName = document.getElementById("scientific-name");
const inputClass = document.getElementById("class");
const inputFamily = document.getElementById("family");
const inputOrder = document.getElementById("order");
const dropdownList = document.getElementById("dropdown-list");

let debounceTimer; //Variável para controlar o tempo do debounce na digitação do usuário

//Adiciona um ouvinte de evento para o input do nome comum, com debounce para evitar muitas requisições
inputCommonName.addEventListener("input", () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(searchTaxonomy, 400);
});

//Fecha o dropdown se o clique acontecer fora do input ou da lista de sugestões
document.addEventListener("click", (e) => {
    const isClickInside = inputCommonName.contains(e.target) || dropdownList.contains(e.target);
    if (!isClickInside) {
        dropdownList.style.visibility = "hidden";
        dropdownList.style.outline = "none";
        dropdownList.classList.remove("active");
    }
});

//Função principal que realiza a busca na API do iNaturalist com base no nome comum digitado
async function searchTaxonomy() {
    const userEntry = inputCommonName.value.trim();

    //Se o campo estiver vazio, limpa tudo e retorna
    if (!userEntry) {
        clearFields();
        dropdownList.innerHTML = "";
        dropdownList.classList.remove("active");
        dropdownList.style.outline = "none";
        return;
    }

    try { //Faz a requisição para a API do iNaturalist, buscando até 200 resultados em português
        const iNatResponse = await fetch(`https://api.inaturalist.org/v1/taxa?q=${encodeURIComponent(userEntry)}&locale=pt-BR&per_page=200`);
        const iNatData = await iNatResponse.json();

        if (!iNatData.results.length) { //Se não encontrou resultados, mostra mensagem e limpa campos
            dropdownList.innerHTML = "<div class='dropdown-item'>Nenhuma espécie encontrada</div>";
            dropdownList.style.visibility = "visible";
            dropdownList.classList.add("active");
            dropdownList.style.outline = "1px solid #ccc";
            clearFields();
            return;
        }

        //Caso tenha resultados, limpa a lista, mostra o dropdown e adiciona os itens
        dropdownList.innerHTML = "";
        dropdownList.classList.add("active");
        dropdownList.style.visibility = "visible";
        dropdownList.style.outline = "1px solid #ccc";

        iNatData.results.forEach(item => {
            const nomePop = item.preferred_common_name || item.name;
            const div = document.createElement("div");
            div.classList.add("dropdown-item");

            const spanName = document.createElement("span");
            spanName.textContent = nomePop;
            spanName.style.marginRight = "10px";
            div.appendChild(spanName);

            if (item.id) {
                const linkINat = document.createElement("a");
                linkINat.href = `https://www.inaturalist.org/taxa/${item.id}`;
                linkINat.target = "_blank";
                linkINat.rel = "noopener noreferrer";
                linkINat.style.color = "#007bff";
                linkINat.style.textDecoration = "none";
                linkINat.style.marginLeft = "5px";

                const icon = document.createElement("i");
                icon.className = "fa-regular fa-circle-question";
                linkINat.appendChild(icon);

                div.appendChild(linkINat);
            }

            //Quando o usuário clica no nome da espécie, chama a função para preencher os campos
            spanName.addEventListener("click", () => selectTaxon(item));
            dropdownList.appendChild(div);
        });
    } catch (error) {
        console.error("❌ Erro ao buscar dados:", error);
        dropdownList.innerHTML = "";
        dropdownList.classList.remove("active");
        dropdownList.style.outline = "none";
        clearFields();
    }
}

function clearFields() { //Limpar os campos de informações taxonômicas
    inputScientificName.value = "";
    inputClass.value = "";
    inputFamily.value = "";
    inputOrder.value = "";
}

async function selectTaxon(taxon) { //Chamada quando o usuário seleciona uma espécie da lista
    dropdownList.innerHTML = "";
    dropdownList.classList.remove("active");
    dropdownList.style.outline = "none";

    const scientificName = taxon.name;
    inputCommonName.value = taxon.preferred_common_name || scientificName;

    try {
        //Busca dados taxonômicos detalhados na API do GBIF usando o nome científico
        const gbifResponse = await fetch(`https://api.gbif.org/v1/species/match?name=${encodeURIComponent(scientificName)}`);
        const gbifData = await gbifResponse.json();

        //Preenche os campos com os dados retornados ou vazio, se não existir
        inputScientificName.value = scientificName || "";
        inputClass.value = gbifData.class || "";
        inputFamily.value = gbifData.family || "";
        inputOrder.value = gbifData.order || "";
    } catch (erro) {
        console.error("❌ Erro ao buscar classificação no GBIF:", erro);
        inputScientificName.value = scientificName || "";
        clearFields();
    }
}