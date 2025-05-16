//Redireciona para 'compartilhar' se logado
const btnCompartilhe = document.getElementById("btn-compartilhe");

btnCompartilhe.addEventListener("click", function () {
    if (sessionStorage.getItem("logado") !== "true") {
        window.location.href = "../login/login.php";
    } else {
        window.location.href = "../compartilhar/compartilhar.php";
    }
});

//Carrega as estatísticas
fetch("../../php_funcoes/estatisticas.php")
    .then(response => response.json())
    .then(data => {
        if (!data.erro) {
            
            document.getElementById("num-registros").textContent = data.registros;
            document.getElementById("num-especies").textContent = data.especies;
            document.getElementById("num-colaboradores").textContent = data.colaboradores;
        } else {
            console.error(data.erro);
        }
    })
    .catch(error => console.error("Erro ao carregar estatísticas:", error));


fetch("../../php_funcoes/destaques.php")
    .then(response => response.json())
    .then(destaques => {
        const barra = document.getElementById("barra-destaques");
        barra.innerHTML = "";

        destaques.forEach(post => {
            const destaque = document.createElement("div");
            destaque.className = "bloco-destaques";

            destaque.innerHTML = `
                <img src="${post.url_imagem}" alt="${post.nome_popular}")">
            `;

            barra.appendChild(destaque);
        });
    });

