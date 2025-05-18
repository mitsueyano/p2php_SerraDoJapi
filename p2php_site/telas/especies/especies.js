function carregarEspecie(nome) {
    fetch(`../../php_funcoes/carregaEspecie.php?nome=${encodeURIComponent(nome)}`)
        .then(res => res.json())
        .then(data => {
            const div = document.getElementById("div-info-especie");
            div.innerHTML = `<h3>${nome}</h3>`;

            if (data.imagens.length === 0) {
                div.innerHTML += "<p>Nenhuma imagem disponível.</p>";
            } else {
                const galeria = document.createElement("div");
                galeria.classList.add("galeria");

                data.imagens.forEach(img => {
                    const item = document.createElement("div");
                    item.classList.add("galeria-item");

                    const imagem = document.createElement("img");
                    imagem.src = img.caminho_imagem;
                    imagem.alt = nome;
                    imagem.classList.add("galeria-img");

                    const info = document.createElement("div");
                    info.classList.add("galeria-info");
                    info.innerHTML = `
                        <small><strong>Usuário:</strong> ${img.usuario}</small><br>
                        <small><strong>Data:</strong> ${img.data}</small><br>
                        <small><strong>Local:</strong> ${img.localizacao}</small>
                    `;

                    item.appendChild(imagem);
                    item.appendChild(info);
                    galeria.appendChild(item);
                });

                div.appendChild(galeria);
            }

            div.innerHTML += `<span id="linknaturalist"><a href="${data.link}" target="_blank">iNaturalist</a></span>`;
        })
        .catch(err => {
            console.error(err);
            document.getElementById("div-info-especie").innerHTML = "<p>Erro ao carregar dados.</p>";
        });
}

document.querySelectorAll('#navbar-letras a').forEach(link => {
  link.addEventListener('click', function(e) {
    e.preventDefault();

    const id = this.getAttribute('href').substring(1);
    const listaEspecies = document.getElementById('lista-especies');
    const alvo = document.getElementById(id);

    if (alvo && listaEspecies) {
      listaEspecies.scrollTo({
        top: alvo.offsetTop,
        behavior: 'smooth'
      });
    }
  });
});

window.addEventListener("load", function () {
    const header = document.getElementById("header");
    window.scrollTo({
        top: header.offsetHeight,
        behavior: "smooth" 
    });
});
