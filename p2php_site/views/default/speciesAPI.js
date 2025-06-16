const inputCommonName = document.getElementById("common-name");
const inputScientificName = document.getElementById("scientific-name");
const inputClass = document.getElementById("class");
const inputFamily = document.getElementById("family");
const inputOrder = document.getElementById("order");
const dropdownList = document.getElementById("dropdown-list");

let debounceTimer;

inputCommonName.addEventListener("input", () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(searchTaxonomy, 400);
});

document.addEventListener("click", (e) => {
    const isClickInside = inputCommonName.contains(e.target) || dropdownList.contains(e.target);
    if (!isClickInside) {
        dropdownList.style.visibility = "hidden";
        dropdownList.style.outline = "none";
        dropdownList.classList.remove("active");
    }
});

async function searchTaxonomy() {
    const userEntry = inputCommonName.value.trim();


    if (!userEntry) {
        clearFields();
        dropdownList.innerHTML = "";
        dropdownList.classList.remove("active");
        dropdownList.style.outline = "none";
        return;
    }

    try {
        const iNatResponse = await fetch(`https://api.inaturalist.org/v1/taxa?q=${encodeURIComponent(userEntry)}&locale=pt-BR&per_page=200`);
        const iNatData = await iNatResponse.json();

        if (!iNatData.results.length) {
            dropdownList.innerHTML = "<div class='dropdown-item'>Nenhuma espécie encontrada</div>";
            dropdownList.style.visibility = "visible";
            dropdownList.classList.add("active");
            dropdownList.style.outline = "1px solid #ccc";
            clearFields();
            return;
        }

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

function clearFields() {
    inputScientificName.value = "";
    inputClass.value = "";
    inputFamily.value = "";
    inputOrder.value = "";
}

async function selectTaxon(taxon) {
    dropdownList.innerHTML = "";
    dropdownList.classList.remove("active");
    dropdownList.style.outline = "none";

    const scientificName = taxon.name;
    inputCommonName.value = taxon.preferred_common_name || scientificName;

    try {
        const gbifResponse = await fetch(`https://api.gbif.org/v1/species/match?name=${encodeURIComponent(scientificName)}`);
        const gbifData = await gbifResponse.json();

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