function carregarEspecies(tipo) {
  fetch(`../../php_funcoes/carregaEspecie.php?tipo=${encodeURIComponent(tipo)}`)
    .then(res => res.json())
    .then(especies => {
      const lista = document.getElementById('lista-especies');
      var letras = document.getElementById("navbar-letras").cloneNode(true);
      lista.innerHTML = '';
      lista.appendChild(letras);
      scroll(); // ativa os eventos dos links

      // ✅ Mensagem padrão ao carregar lista
      const divInfo = document.getElementById('div-info-especie');
      divInfo.innerHTML = '<p>Clique em uma espécie para ver mais informações.</p>';

      if (especies.length === 0) {
        lista.innerHTML = '<p>Nenhuma espécie encontrada.</p>';
        return;
      }

      const especiesPorLetra = {};
      especies.forEach(nome => {
        const letra = nome[0].toUpperCase();
        if (!especiesPorLetra[letra]) especiesPorLetra[letra] = [];
        especiesPorLetra[letra].push(nome);
      });

      Object.keys(especiesPorLetra).sort().forEach(letra => {
        const marcador = document.createElement('span');
        marcador.id = `marcador-letra-${letra}`;
        marcador.className = 'marcador-letra';
        lista.appendChild(marcador);

        const tituloLetra = document.createElement('h3');
        tituloLetra.id = `letra-${letra}`;
        tituloLetra.textContent = letra;
        lista.appendChild(tituloLetra);

        especiesPorLetra[letra].forEach(nome => {
          const div = document.createElement('div');
          div.className = 'especie-item';
          div.textContent = nome;
          div.onclick = () => infoEspecie(nome);
          lista.appendChild(div);
        });
      });

    })
    .catch(err => {
      console.error('Erro ao carregar espécies:', err);
    });
}


// Código para navegação por letras na navbar-letras:
function scroll(){
    document.querySelectorAll('#navbar-letras a').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        const id = this.getAttribute('href').substring(1);
        const listaEspecies = document.getElementById('lista-especies');
        const alvo = document.getElementById(id);
        if (alvo && listaEspecies) {
        const offsetRelativo = alvo.offsetTop - listaEspecies.offsetTop;
        listaEspecies.scrollTo({
            top: offsetRelativo,
            behavior: 'smooth'
        });
        }
    });
    });
}

// Controle de botão ativo no filtro:
const botoesFiltro = document.querySelectorAll('#filtro button');
botoesFiltro.forEach(botao => {
  botao.addEventListener('click', () => {
    botoesFiltro.forEach(b => b.classList.remove('ativo'));
    botao.classList.add('ativo');
    carregarEspecies(botao.id.replace('btn-', ''));
  });
});

function infoEspecie(nome) {
  fetch(`../../php_funcoes/buscaEspecie.php?especie=${encodeURIComponent(nome)}`)
    .then(res => res.text())
    .then(texto => {
      try {
        const dados = JSON.parse(texto);
        const divInfo = document.getElementById('div-info-especie');
        divInfo.innerHTML = '';

        if (dados.erro) {
          divInfo.innerHTML = `<p>Erro: ${dados.erro}</p>`;
          console.error('Erro do PHP:', dados.erro);
          return;
        }

        if (dados.length === 0) {
          divInfo.innerHTML = '<p>Informações não encontradas para esta espécie.</p>';
          return;
        }

        const titulo = document.createElement('div');
        titulo.id="divtitulo"
        titulo.innerHTML = `<h3 id="titulo">${nome}</h3>`;
        divInfo.appendChild(titulo);

        // Criar div da galeria
        const galeria = document.createElement('div');
        galeria.className = 'galeria';

        dados.forEach(registro => {
          // Se tiver imagem, cria um item na galeria com estrutura completa (imagem + info)
          const galeriaItem = document.createElement('div');
          galeriaItem.className = 'galeria-item';

          // Imagem
          const imagem = document.createElement('img');
          imagem.src = registro.url_imagem;
          imagem.alt = nome;
          imagem.className = 'galeria-img';

          // Info da imagem
          const info = document.createElement('div');
          info.className = 'galeria-info';
          info.innerHTML = `
            <span class="info"><strong>Usuário:</strong> ${registro.usuario || 'Desconhecido'}</span><br>
            <span class="info"><strong>Data:</strong> ${registro.data_observacao || 'Sem data'}</span><br>
            <span class="info"><strong>Local:</strong> ${registro.nome_lugar || 'Sem localização'}</span>
          `;

          galeriaItem.appendChild(imagem);
          galeriaItem.appendChild(info);
          galeria.appendChild(galeriaItem);
        });

        divInfo.appendChild(galeria);

      } catch (e) {
        console.error('Erro ao parsear JSON:', e, 'Texto recebido:', texto);
        document.getElementById('div-info-especie').innerHTML = '<p>Erro ao carregar informações.</p>';
      }
    })
    .catch(err => {
      console.error('Erro ao buscar dados da espécie:', err);
      document.getElementById('div-info-especie').innerHTML = '<p>Erro ao carregar informações.</p>';
    });
}


// ✅ Carrega tudo ao abrir a página
window.addEventListener('load', () => {
    carregarEspecies('todos');

    // ✅ Mensagem inicial na div de info
    document.getElementById('div-info-especie').innerHTML = '<p>Clique em uma espécie para ver mais informações.</p>';

    const header = document.getElementById("header");
    window.scrollTo({
        top: header.offsetHeight,
        behavior: "smooth"
    });
});
