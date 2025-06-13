function loadSpecies(category) {
  const speciesItems = document.getElementById('species-items');
  const navbarLetters = document.getElementById("navbar-letters");
  const noSpecies = document.getElementById("nospecies");
  const speciesList = document.getElementById('species-list');
  const divInfo = document.getElementById('div-info-specie');

  if (category === 'naoidentificado') {
    infoSpecie('Não identificado');
    speciesItems.innerHTML = ''; // limpa só os itens das espécies
    navbarLetters.classList.add('hidden');  // esconde filtro letras
    noSpecies.classList.remove('hidden');   // mostra "sem espécies"
    divInfo.innerHTML = '<p>Clique em um tipo para ver mais informações.</p>';

    // Esconder lista e expandir div de info
    speciesList.classList.add('hidden');
    divInfo.classList.add('full-width-info');

    // Esconde o link do iNaturalist se existir
    setTimeout(() => {
      const inatLink = document.getElementById('linkinaturalist');
      if (inatLink) inatLink.style.display = 'none';
    }, 50); // aguarda a renderização do conteúdo

    return;
  } else {
    navbarLetters.classList.remove('hidden');
    noSpecies.classList.add('hidden');
    divInfo.innerHTML = '<p>Clique em um tipo para ver mais informações.</p>';

    // Restaurar exibição padrão
    speciesList.classList.remove('hidden');
    divInfo.classList.remove('full-width-info');
  }

  fetch(`../../php/loadspecies.php?category=${encodeURIComponent(category)}`)
    .then(res => res.json())
    .then(species => {
      speciesItems.innerHTML = '';  // limpa só os itens das espécies
      scroll();

      if (species.length === 0) {
        navbarLetters.classList.add("hidden");
        noSpecies.classList.remove("hidden");
        return;
      } else {
        navbarLetters.classList.remove("hidden");
        noSpecies.classList.add("hidden");
      }

      const speciesPerLetter = {};
      species.forEach(name => {
        const letter = name.especie[0].toUpperCase();
        if (!speciesPerLetter[letter]) speciesPerLetter[letter] = [];
        speciesPerLetter[letter].push({ especie: name.especie, classe: name.classe });
      });

      Object.keys(speciesPerLetter).sort().forEach(letter => {
        const marker = document.createElement('span');
        marker.id = `letter-marker-${letter}`;
        marker.className = 'letter-marker';
        speciesItems.appendChild(marker);

        const titleletter = document.createElement('h3');
        titleletter.id = `letter-${letter}`;
        titleletter.textContent = letter;
        speciesItems.appendChild(titleletter);

        speciesPerLetter[letter].forEach(a => {
          const div = document.createElement('div');
          const spanSpecie = document.createElement('span');
          const spanClass = document.createElement('span');
          spanSpecie.textContent = a.especie;
          spanSpecie.classList.add('spanSpecie');
          spanClass.textContent = a.classe;
          spanClass.classList.add('spanClass');
          div.appendChild(spanSpecie);
          div.appendChild(spanClass);
          div.className = 'specie-item';
          div.onclick = () => {
            infoSpecie(a.especie);

            // Após carregar info, tenta mostrar o link do iNaturalist (se estiver oculto)
            setTimeout(() => {
              const inatLink = document.getElementById('linkinaturalist');
              if (inatLink) inatLink.style.display = '';
            }, 100);
          }
          speciesItems.appendChild(div);
        });
      });

      filter();
    })
    .catch(err => {
      console.error('Erro ao carregar espécies:', err);
    });
}



function scroll(){
    document.querySelectorAll('#navbar-letters a').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        const id = this.getAttribute('href').substring(1);
        const listspecies = document.getElementById('species-list');
        const target = document.getElementById(id);
        if (target && listspecies) {
        const offsetRelative = target.offsetTop - listspecies.offsetTop;
        listspecies.scrollTo({
            top: offsetRelative,
            behavior: 'smooth'
        });
        }
    });
    });
}

const btnsFilter = document.querySelectorAll('button');
btnsFilter.forEach(button => {
  button.addEventListener('click', () => {
    btnsFilter.forEach(b => b.classList.remove('active'));
    button.classList.add('active');
    loadSpecies(button.value);
  });
});

function infoSpecie(name) {
  fetch(`../../php/speciesearch.php?specie=${encodeURIComponent(name)}`)
    .then(res => res.text())
    .then(text => {
      try {
        const data = JSON.parse(text);
        const divInfo = document.getElementById('div-info-specie');
        divInfo.innerHTML = '';

        if (data.error) {
          divInfo.innerHTML = `<p>Erro: ${data.error}</p>`;
          console.error('Erro do PHP:', data.error);
          return;
        }

        if (data.length === 0) {
          divInfo.innerHTML = '<p>Informações não encontradas para este tipo.</p>';
          return;
        }

        const title = document.createElement('div');
        title.id="divtitle"
        title.innerHTML = `<h3 id="title">${name}</h3>`;
        divInfo.appendChild(title);

        const gallery = document.createElement('div');
        gallery.className = 'gallery';

        gallery.innerHTML += `<span id="linkinaturalist"><a href="https://www.inaturalist.org/search?q=${encodeURIComponent(name)}" target="_blank">iNaturalist</a></span>`;

        data.forEach(record => {
          const galleryItem = document.createElement('div');
          galleryItem.className = 'gallery-item';

          const image = document.createElement('img');
          image.src = record.url_imagem;
          image.alt = name;
          image.className = 'gallery-img';

          const info = document.createElement('div');
          info.className = 'gallery-info';
          info.innerHTML = `
            <span class="info"><strong>Usuário:</strong> ${record.usuario || 'Desconhecido'}</span><br>
            <span class="info"><strong>Data:</strong> ${formatDate(record.data_observacao)}</span><br>
            <span class="info"><strong>Local:</strong> ${record.nome_lugar || 'Sem localização'}</span>
          `;

          galleryItem.appendChild(image);
          galleryItem.appendChild(info);
          gallery.appendChild(galleryItem);
        });

        divInfo.appendChild(gallery);
        document.getElementById("div-info-specie").scrollIntoView({behavior: "smooth", block: "nearest"})

      } catch (e) {
        console.error('Erro ao parsear JSON:', e, 'Texto recebido:', text);
        document.getElementById('div-info-specie').innerHTML = '<p>Erro ao carregar informações.</p>';
      }
    })
    .catch(err => {
      console.error('Erro ao buscar data da espécie:', err);
      document.getElementById('div-info-specie').innerHTML = '<p>Erro ao carregar informações.</p>';
    });
}

function formatDate(dataISO) {
  if (!dataISO) return 'Sem data';
  const [year, month, day] = dataISO.split('-');
  return `${day}/${month}/${year}`;
}


window.addEventListener('load', () => {
    loadSpecies('todos');

    document.getElementById('div-info-specie').innerHTML = '<p>Clique em uma espécie para ver mais informações.</p>';

    const header = document.getElementById("header");
    window.scrollTo({
        top: header.offsetHeight,
        behavior: "smooth"
    });
});

const input = document.getElementById('search-bar');
const noSpecies = document.getElementById('nospecies');
let debounceTimeout;

const filter = () => {
  let filtroAtual = 'todos';
  const items = document.querySelectorAll('.specie-item');
  const letterHeaders = document.querySelectorAll('#species-list h3[id^="letter-"]');
  const letterMarkers = document.querySelectorAll('.letter-marker');
   let algumVisivel = false;
    clearTimeout(debounceTimeout);
    debounceTimeout = setTimeout(() => {
        const termo = input.value.trim().toLowerCase();
        let algumVisivel = false;

        items.forEach(item => {
             const nome = item.querySelector('.spanSpecie').textContent.toLowerCase();
    const classe = item.querySelector('.spanClass').textContent.toLowerCase();


    const passaFiltroTipo = filtroAtual === 'todos' || classe.includes(filtroAtual);
    const passaFiltroBusca = nome.includes(termo) || classe.includes(termo);

    const visivel = passaFiltroTipo && passaFiltroBusca;

    item.style.display = visivel ? 'flex' : 'none';
    if (visivel) algumVisivel = true;
        });

        noSpecies.classList.toggle('hidden', algumVisivel);

         letterHeaders.forEach(h3 => {
    const letter = h3.id.split('-')[1];
    const marker = document.getElementById('letter-marker-' + letter);

    let nextH3 = h3.nextElementSibling;
    let temItemVisivel = false;

    while (nextH3 && !(nextH3.tagName === 'H3' && nextH3.id.startsWith('letter-'))) {
      if (nextH3.classList.contains('specie-item') && nextH3.style.display !== 'none') {
        temItemVisivel = true;
        break;
      }
      nextH3 = nextH3.nextElementSibling;
    }

    h3.style.display = temItemVisivel ? 'flex' : 'none';
    if (marker) marker.style.display = temItemVisivel ? 'inline' : 'none';
  });
        updateLetterNavbarVisibility();

    }, 300); 
}

input.addEventListener('input', filter);

function updateLetterNavbarVisibility() {
    const allSpecies = document.querySelectorAll('.specie-item');
    const visibleSpecies = [...allSpecies].filter(item => item.style.display !== 'none');

    const visibleLetters = new Set();

    visibleSpecies.forEach(item => {
        const specieName = item.querySelector('.spanSpecie').textContent.trim();
        const firstLetter = specieName.charAt(0).toUpperCase();
        visibleLetters.add(firstLetter);
    });

    const letterLinks = document.querySelectorAll('#navbar-letters a');
    letterLinks.forEach(link => {
        const letter = link.dataset.letter;
        if (visibleLetters.has(letter)) {
            link.classList.remove('disabled');
        } else {
            link.classList.add('disabled');
        }
    });
}

