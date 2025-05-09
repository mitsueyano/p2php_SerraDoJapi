//--- Função para mostrar 'Entre/Cadastre-se' ou 'Perfil' de acordo coma  sessão ---//

document.addEventListener("DOMContentLoaded", function () {
    if (sessionStorage.getItem("logado") === "true") {
        document.getElementById("login-link").style.display = "none";
        document.getElementById("perfil-link").style.display = "flex";
    } else {
        document.getElementById("login-link").style.display = "flex";
        document.getElementById("perfil-link").style.display = "none";
    }
});
