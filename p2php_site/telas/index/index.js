const btnCompartilhe = document.getElementById("btn-compartilhe");

    btnCompartilhe.addEventListener("click", function(event) {

        if (sessionStorage.getItem("logado") !== "true") {
            window.location.href = "../login/login.php";
        } else {
            window.location.href = "../compartilhar/compartilhar.php";
        }
    });