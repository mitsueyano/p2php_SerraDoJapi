// Inicializa o mapa em Jundiaí
const map = L.map('map').setView([-23.1857, -46.8978], 13);

// Adiciona o tile layer do OpenStreetMap
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
}).addTo(map);

let marker;

// Função para buscar endereço pelo latlng e preencher o campo nomelugar
function preencherNomeLugar(latlng) {
    const campoNome = document.getElementById('nomelugar');

    const url = `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${latlng.lat}&lon=${latlng.lng}`;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data && data.address) {
                const addr = data.address;

                const pontoReferencia = addr.amenity || addr.shop || addr.tourism || addr.building || "";
                const numero = addr.house_number || "";
                const rua = addr.road || addr.pedestrian || addr.footway || addr.cycleway || addr.path || "";
                const bairro = addr.neighbourhood || addr.suburb || "";
                const cidade = addr.city || addr.town || addr.village || "";

                let partes = [];

                if (pontoReferencia) partes.push(pontoReferencia);
                if (rua) {
                    if (numero) {
                        partes.push(`${rua}, ${numero}`);
                    } else {
                        partes.push(rua);
                    }
                }
                if (bairro) partes.push(bairro);
                if (cidade) partes.push(cidade);

                campoNome.value = partes.join(", ") || data.display_name || "";
            } else {
                campoNome.value = "";
            }
        })
        .catch(err => {
            console.error("Erro ao buscar nome do lugar:", err);
            campoNome.value = "";
        });
}


// Geocoder (barra de pesquisa)
L.Control.geocoder({
    defaultMarkGeocode: false
})
.on('markgeocode', function(e) {
    const latlng = e.geocode.center;
    map.setView(latlng, 15);

    if (marker) {
        marker.setLatLng(latlng);
    } else {
        marker = L.marker(latlng).addTo(map);
    }

    document.getElementById('latitude').value = latlng.lat.toFixed(6);
    document.getElementById('longitude').value = latlng.lng.toFixed(6);

    let nomeLugar = "";

    if (e.geocode.properties && e.geocode.properties.address) {
        const addr = e.geocode.properties.address;

        const rua = addr.road || addr.pedestrian || addr.footway || addr.cycleway || addr.path || "";
        const numero = addr.house_number || "";
        const cidade = addr.city || addr.town || addr.village || "";

        let partes = [];
        if (rua) partes.push(rua);
        if (numero) partes.push(numero);
        if (cidade) partes.push(cidade);


        nomeLugar = partes.join(", ") || e.geocode.name || "";
    } else {
        nomeLugar = e.geocode.name || "";
    }

    document.getElementById('nomelugar').value = nomeLugar;
})
.addTo(map);

// Ao clicar no mapa preenche latitude, longitude e busca o nome do lugar (se nomelugar vazio)
map.on('click', function (e) {
    const { lat, lng } = e.latlng;

    if (marker) {
        marker.setLatLng(e.latlng);
    } else {
        marker = L.marker(e.latlng).addTo(map);
    }

    document.getElementById('latitude').value = lat.toFixed(6);
    document.getElementById('longitude').value = lng.toFixed(6);

    preencherNomeLugar(e.latlng); // Já chama a função de geocodificação reversa
});

// ... o restante do código continua igual

// ELEMENTOS
const inputNomePopular = document.getElementById("nomepopular");
const inputNomeCientifico = document.getElementById("nomecientifico");
const inputClasse = document.getElementById("classe");
const inputFamilia = document.getElementById("familia");
const inputOrdem = document.getElementById("ordem");
const dropdownList = document.getElementById("dropdown-list");

let debounceTimer;

// Evento para disparar a busca com debounce (400ms)
inputNomePopular.addEventListener("input", () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(buscarTaxonomia, 400);
});

// Fecha o dropdown apenas se o clique for fora do campo e do dropdown
document.addEventListener("click", (e) => {
    const isClickInside = inputNomePopular.contains(e.target) || dropdownList.contains(e.target);
    if (!isClickInside) {
        dropdownList.style.visibility = "hidden";
        dropdownList.style.outline = "none";
        dropdownList.classList.remove("ativo");
    }
});

// Função principal para buscar no iNaturalist e preencher dropdown
async function buscarTaxonomia() {
    const entradaUsuario = inputNomePopular.value.trim();

    // Limpa campos e dropdown se campo vazio
    if (!entradaUsuario) {
        limparCampos();
        dropdownList.innerHTML = "";
        dropdownList.classList.remove("ativo");
        dropdownList.style.outline = "none";
        return;
    }

    try {
        // Busca no iNaturalist
        const iNatResponse = await fetch(`https://api.inaturalist.org/v1/taxa?q=${encodeURIComponent(entradaUsuario)}&locale=pt-BR&per_page=200`);
        const iNatData = await iNatResponse.json();

        if (!iNatData.results.length) {
            dropdownList.innerHTML = "<div class='dropdown-item'>Nenhuma espécie encontrada</div>";
            dropdownList.style.visibility = "visible";
            dropdownList.classList.add("ativo");
            dropdownList.style.outline = "1px solid #ccc";
            limparCampos();
            return;
        }

        dropdownList.innerHTML = "";
        dropdownList.classList.add("ativo");
        dropdownList.style.visibility = "visible";
        dropdownList.style.outline = "1px solid #ccc";

        iNatData.results.forEach(item => {
            const nomePop = item.preferred_common_name || item.name;
            const div = document.createElement("div");
            div.classList.add("dropdown-item");

            // Nome
            const spanNome = document.createElement("span");
            spanNome.textContent = nomePop;
            spanNome.style.marginRight = "10px";
            div.appendChild(spanNome);

            // Wikipedia
            if (item.wikipedia_url) {
                const linkWiki = document.createElement("a");
                linkWiki.href = item.wikipedia_url.replace("en.wikipedia", "pt.wikipedia");
                linkWiki.target = "_blank";
                linkWiki.rel = "noopener noreferrer";
                linkWiki.style.color = "#007bff";
                linkWiki.style.textDecoration = "none";
                linkWiki.style.marginLeft = "5px";

                const icon = document.createElement("i");
                icon.className = "fa-regular fa-circle-question";
                linkWiki.appendChild(icon);

                div.appendChild(linkWiki);
            }

            spanNome.addEventListener("click", () => selecionarTaxon(item));
            dropdownList.appendChild(div);
        });
    } catch (erro) {
        console.error("❌ Erro ao buscar dados:", erro);
        dropdownList.innerHTML = "";
        dropdownList.classList.remove("ativo");
        dropdownList.style.outline = "none";
        limparCampos();
    }
}

// Função para limpar campos taxonômicos
function limparCampos() {
    inputNomeCientifico.value = "";
    inputClasse.value = "";
    inputFamilia.value = "";
    inputOrdem.value = "";
}

// Ao selecionar uma espécie do dropdown, preenche campos e limpa lista
async function selecionarTaxon(taxon) {
    dropdownList.innerHTML = "";
    dropdownList.classList.remove("ativo");
    dropdownList.style.outline = "none";

    const nomeCientifico = taxon.name;
    inputNomePopular.value = taxon.preferred_common_name || nomeCientifico;

    try {
        const gbifResponse = await fetch(`https://api.gbif.org/v1/species/match?name=${encodeURIComponent(nomeCientifico)}`);
        const gbifData = await gbifResponse.json();

        inputNomeCientifico.value = nomeCientifico || "";
        inputClasse.value = gbifData.class || "";
        inputFamilia.value = gbifData.family || "";
        inputOrdem.value = gbifData.order || "";
    } catch (erro) {
        console.error("❌ Erro ao buscar classificação no GBIF:", erro);
        inputNomeCientifico.value = nomeCientifico || "";
        limparCampos();
    }
}
