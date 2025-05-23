function loadSpecies(category) {
  fetch(`../../php/loadspecies.php?category=${encodeURIComponent(category)}`)
    .then(res => res.json())
    .then(species => {
      const list = document.getElementById('species-list');
      var letters = document.getElementById("navbar-letters").cloneNode(true);
      var nospecies = document.getElementById("nospecies").cloneNode(true);
      list.innerHTML = '';
      list.appendChild(letters);
      list.appendChild(nospecies);
      scroll();

      const divInfo = document.getElementById('div-info-specie');
      divInfo.innerHTML = '<p>Clique em um tipo para ver mais informações.</p>';

      if (species.length === 0) {
        document.getElementById("navbar-letters").classList.add("hidden");
        document.getElementById("nospecies").classList.remove("hidden");
        return;
      } else{
        document.getElementById("navbar-letters").classList.remove("hidden");
        document.getElementById("nospecies").classList.add("hidden");
      }
      const speciesPerLetter = {};
      species.forEach(name => {
        console.log(name);
        const letter = name.especie[0].toUpperCase();
        if (!speciesPerLetter[letter]) speciesPerLetter[letter] = [];
        speciesPerLetter[letter].push({especie: name.especie, classe: name.classe});
      });


      Object.keys(speciesPerLetter).sort().forEach(letter => {
        const marker = document.createElement('span');
        marker.id = `letter-marker-${letter}`;
        marker.className = 'letter-marker';
        list.appendChild(marker);

        const titleletter = document.createElement('h3');
        titleletter.id = `letter-${letter}`;
        titleletter.textContent = letter;
        list.appendChild(titleletter);

        speciesPerLetter[letter].forEach(a => {
          const div = document.createElement('div');
          const spanSpecie = document.createElement('span');
          const spanClass = document.createElement('span');
          spanSpecie.textContent = a.especie;
          spanSpecie.classList.add('spanSpecie')
          spanClass.textContent = a.classe;
          spanClass.classList.add('spanClass');
          div.appendChild(spanSpecie);
          div.appendChild(spanClass);
          div.className = 'specie-item';
          div.onclick = () => infoSpecie(a.especie);
          list.appendChild(div);
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

const btnsFilter = document.querySelectorAll('#filter button');
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

