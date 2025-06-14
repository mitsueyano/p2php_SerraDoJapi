
//Carrega as estatísticas
fetch("../../php/statistics.php")
    .then(response => response.json())
    .then(data => {
        if (!data.erro) {
            
            document.getElementById("num-records").textContent = data.registros;
            document.getElementById("num-species").textContent = data.especies;
            document.getElementById("num-collaborators").textContent = data.colaboradores;
        } else {
            console.error(data.erro);
        }
    })
    .catch(error => console.error("Erro ao carregar estatísticas:", error));


fetch("../../php/loadhighlights.php")
    .then(response => response.json())
    .then(highlights => {
        const bar = document.getElementById("highlights-bar");
        bar.innerHTML = "";

        highlights.forEach(post => {
            const highlight = document.createElement("div");
            highlight.className = "box-highlights";

            highlight.innerHTML = `
                <img src="${post.url_imagem}" alt="${post.nome_popular}") onclick="window.location.href = '../postdetails/postdetails.php?id=${post.id}'">
                <div class="description">
                    <span>${post.nome_popular}</span>
                    <span>(${post.especie})</span>
                    <span><i class="fa-solid fa-user"></i> ${post.nomeusuario} ${post.sobrenome}</span>
                </div>
            `;

            bar.appendChild(highlight);
        });
    });

