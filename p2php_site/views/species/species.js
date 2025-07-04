let currentCategory = "todos";

//Função principal que carrega as espécies de uma categoria
function loadSpecies(category) {
  currentCategory = category;
  const speciesItems = document.getElementById("species-items");
  const navbarLetters = document.getElementById("navbar-letters");
  const noSpecies = document.getElementById("nospecies");
  const speciesList = document.getElementById("species-list");
  const divInfo = document.getElementById("div-info-specie");

  if (category === "naoidentificado") {
    // Caso a categoria seja "não identificado"
    infoSpecie("Não identificado", true);
    speciesItems.innerHTML = "";
    navbarLetters.classList.add("hidden");
    noSpecies.classList.remove("hidden");
    divInfo.innerHTML =
      "<p>Clique em uma espécie para ver mais informações.</p>";

    speciesList.classList.add("hidden");
    divInfo.classList.add("full-width-info");

    return;
  } else {
    //Caso contrário, mostra os elementos padrão
    navbarLetters.classList.remove("hidden");
    noSpecies.classList.add("hidden");
    divInfo.innerHTML =
      "<p>Clique em uma espécie para ver mais informações.</p>";

    speciesList.classList.remove("hidden");
    divInfo.classList.remove("full-width-info");
  }

  fetch(`../../php/loadspecies.php?category=${encodeURIComponent(category)}`)
    .then((res) => res.json())
    .then((species) => {
      speciesItems.innerHTML = "";
      scroll();

      if (species.length === 0) {
        //Caso nenhuma espécie seja retornada
        navbarLetters.classList.add("hidden");
        noSpecies.classList.remove("hidden");
        return;
      } else {
        navbarLetters.classList.remove("hidden");
        noSpecies.classList.add("hidden");
      }

      //Agrupamento por letra inicial da espécie
      const speciesPerLetter = {};
      species.forEach((name) => {
        const letter = name.especie[0].toUpperCase();
        if (!speciesPerLetter[letter]) speciesPerLetter[letter] = [];
        speciesPerLetter[letter].push({
          especie: name.especie,
          classe: name.classe,
          popular: name.nome_popular,
        });
      });

      //Monta o HTML para cada letra
      Object.keys(speciesPerLetter)
        .sort()
        .forEach((letter) => {
          const marker = document.createElement("span");
          marker.id = `letter-marker-${letter}`;
          marker.className = "letter-marker";
          speciesItems.appendChild(marker);

          const titleletter = document.createElement("h3");
          titleletter.id = `letter-${letter}`;
          titleletter.textContent = letter;
          speciesItems.appendChild(titleletter);

          speciesPerLetter[letter].forEach((a) => {
            const div = document.createElement("div");
            const spanSpecie = document.createElement("span");
            const spanClass = document.createElement("span");
            spanSpecie.innerHTML = a.popular
              ? `${a.especie} (<em>${a.popular}</em>)`
              : a.especie;

            spanClass.textContent = a.classe;
            spanSpecie.classList.add("spanSpecie");
            spanClass.classList.add("spanClass");
            div.appendChild(spanSpecie);
            div.appendChild(spanClass);
            div.className = "specie-item";
            div.onclick = () => {
              //Ao clicar na espécie, mostra os detalhes
              infoSpecie(a.especie, currentCategory === "naoidentificado");
              const inatLink = document.getElementById("linkinaturalist");
              if (inatLink)
                inatLink.style.display =
                  currentCategory === "naoidentificado" ? "none" : "";
            };
            speciesItems.appendChild(div);
          });
        });

      filter();
    })
    .catch((err) => {
      console.error("Erro ao carregar espécies:", err);
    });
}

function scroll() {
  //Adiciona scroll suave ao clicar nas letras da navbar
  document.querySelectorAll("#navbar-letters a").forEach((link) => {
    link.addEventListener("click", function (e) {
      e.preventDefault();
      const id = this.getAttribute("href").substring(1);
      const listspecies = document.getElementById("species-list");
      const target = document.getElementById(id);
      if (target && listspecies) {
        const offsetRelative = target.offsetTop - listspecies.offsetTop;
        listspecies.scrollTo({
          top: offsetRelative,
          behavior: "smooth",
        });
      }
    });
  });
}

//Botões de filtro por categoria
const btnsFilter = document.querySelectorAll("button");
btnsFilter.forEach((button) => {
  button.addEventListener("click", () => {
    btnsFilter.forEach((b) => b.classList.remove("active"));
    button.classList.add("active");
    loadSpecies(button.value);
  });
});

//Mostra os detalhes de uma espécie
function infoSpecie(name, hideLink = false) {
  fetch(`../../php/speciesearch.php?specie=${encodeURIComponent(name)}`)
    .then((res) => res.text())
    .then((text) => {
      try {
        const data = JSON.parse(text);
        const divInfo = document.getElementById("div-info-specie");
        divInfo.innerHTML = "";

        if (data.error) {
          divInfo.innerHTML = `<p>Erro: ${data.error}</p>`;
          console.error("Erro do PHP:", data.error);
          return;
        }

        if (data.length === 0) {
          divInfo.innerHTML =
            "<p>Informações não encontradas para esta espécie.</p>";
          return;
        }

        const title = document.createElement("div");
        title.id = "divtitle";

        let displayTitle =
          name === "Não identificado"
            ? "Espécies não identificadas"
            : `<span>${name}</span><br><span class="popular">(${data[0].nome_popular})</span>`;

        title.innerHTML = `<span id="title">${displayTitle}</span>`;

        if (!hideLink) {
          //Link para o iNaturalist
          title.innerHTML += `<span id="linkinaturalist">Mais informações sobre esta espécie em <a href="https://www.inaturalist.org/search?q=${encodeURIComponent(
            name
          )}" target="_blank">iNaturalist</a></span>`;
        }

        divInfo.appendChild(title);

        const gallery = document.createElement("div");
        gallery.className = "gallery";

        //Cria os cards com imagens e informações
        data.forEach((record) => {
          const galleryItem = document.createElement("div");
          galleryItem.className = "gallery-item";

          const image = document.createElement("img");
          image.src = record.url_imagem;
          image.alt = name;
          image.className = "gallery-img";
          image.id = `${record.id}`;
          image.onclick = () => {
            window.location.href = `../postdetails/postdetails.php?id=${
              record.id
            }&specie=${encodeURIComponent(name)}&category=${encodeURIComponent(
              currentCategory
            )}`;
          };

          const info = document.createElement("div");
          info.className = "gallery-info";
          info.innerHTML = `
            <span class="info user"><strong><i class="fa-solid fa-camera"></i></strong> 
              ${
                record.usuario
                  ? `<a href="../profile/profile.php?username=${record.usuario}">${record.usuario}</a>`
                  : "Desconhecido"
              }
            </span>
            <span class="info"><strong> <i class="fa-solid fa-calendar"></i></strong> ${formatDate(
              record.data_observacao
            )}</span>
            <span class="info"><strong>  <i class="fa-solid fa-location-dot"></i></strong> ${
              record.nome_lugar || "Sem localização"
            }</span>
          `;
          galleryItem.appendChild(image);
          galleryItem.appendChild(info);
          gallery.appendChild(galleryItem);
        });

        divInfo.appendChild(gallery);
        divInfo.scrollIntoView({ behavior: "smooth", block: "nearest" });
      } catch (e) {
        console.error("Erro ao parsear JSON:", e, "Texto recebido:", text);
        document.getElementById("div-info-specie").innerHTML =
          "<p>Erro ao carregar informações.</p>";
      }
    })
    .catch((err) => {
      console.error("Erro ao buscar data da espécie:", err);
      document.getElementById("div-info-specie").innerHTML =
        "<p>Erro ao carregar informações.</p>";
    });
}

// Formata a data
function formatDate(dataISO) {
  if (!dataISO) return "Sem data";
  const [year, month, day] = dataISO.split("-");
  return `${day}/${month}/${year}`;
}

window.addEventListener("load", () => {
  const params = new URLSearchParams(window.location.search);
  const categoryFromUrl = params.get("category") || "todos";

  //Marca o botão ativo conforme a URL
  const btnsFilter = document.querySelectorAll("button");
  btnsFilter.forEach((button) => {
    button.classList.toggle("active", button.value === categoryFromUrl);
  });

  loadSpecies(categoryFromUrl); //Carrega a categoria da URL

  const lastSpecieSearched = params.get("lastSpecieSearched");
  if (lastSpecieSearched) {
    setTimeout(() => {
      infoSpecie(lastSpecieSearched, categoryFromUrl === "naoidentificado");
    }, 300);
  } else {
    document.getElementById("div-info-specie").innerHTML =
      "<p>Clique em uma espécie para ver mais informações.</p>";
  }

  const header = document.getElementById("header");
  window.scrollTo({
    top: header.offsetHeight,
    behavior: "smooth",
  });
});

//Filtro por nome ou classe com debounce
const input = document.getElementById("search-bar");
const noSpecies = document.getElementById("nospecies");
let debounceTimeout;

const filter = () => {
  let filtroAtual = "todos";
  const items = document.querySelectorAll(".specie-item");
  const letterHeaders = document.querySelectorAll(
    '#species-list h3[id^="letter-"]'
  );
  const letterMarkers = document.querySelectorAll(".letter-marker");
  let algumVisivel = false;
  clearTimeout(debounceTimeout);
  debounceTimeout = setTimeout(() => {
    const termo = input.value.trim().toLowerCase();
    let algumVisivel = false;

    items.forEach((item) => { //Filtra os itens conforme o termo de busca
      const nome = item.querySelector(".spanSpecie").textContent.toLowerCase();
      const classe = item.querySelector(".spanClass").textContent.toLowerCase();

      const passaFiltroTipo =
        filtroAtual === "todos" || classe.includes(filtroAtual);
      const passaFiltroBusca = nome.includes(termo) || classe.includes(termo);

      const visivel = passaFiltroTipo && passaFiltroBusca;

      item.style.display = visivel ? "flex" : "none";
      if (visivel) algumVisivel = true;
    });

    noSpecies.classList.toggle("hidden", algumVisivel);

    //Atualiza cabeçalhos e marcadores de letra
    letterHeaders.forEach((h3) => {
      const letter = h3.id.split("-")[1];
      const marker = document.getElementById("letter-marker-" + letter);

      let nextH3 = h3.nextElementSibling;
      let temItemVisivel = false;

      while (
        nextH3 &&
        !(nextH3.tagName === "H3" && nextH3.id.startsWith("letter-"))
      ) {
        if (
          nextH3.classList.contains("specie-item") &&
          nextH3.style.display !== "none"
        ) {
          temItemVisivel = true;
          break;
        }
        nextH3 = nextH3.nextElementSibling;
      }

      h3.style.display = temItemVisivel ? "flex" : "none";
      if (marker) marker.style.display = temItemVisivel ? "inline" : "none";
    });
    updateLetterNavbarVisibility();
  }, 300);
};

//Atualiza as letras disponíveis na navbar
input.addEventListener("input", filter);

function updateLetterNavbarVisibility() {
  const allSpecies = document.querySelectorAll(".specie-item");
  const visibleSpecies = [...allSpecies].filter(
    (item) => item.style.display !== "none"
  );

  const visibleLetters = new Set();

  visibleSpecies.forEach((item) => {
    const specieName = item.querySelector(".spanSpecie").textContent.trim();
    const firstLetter = specieName.charAt(0).toUpperCase();
    visibleLetters.add(firstLetter);
  });

  const letterLinks = document.querySelectorAll("#navbar-letters a");
  letterLinks.forEach((link) => {
    const letter = link.dataset.letter;
    if (visibleLetters.has(letter)) {
      link.classList.remove("disabled");
    } else {
      link.classList.add("disabled");
    }
  });
}
