document.addEventListener("DOMContentLoaded", function () {
    if (sessionStorage.getItem("loggedin") === "true") {
        document.getElementById("login-link").style.display = "none";
        document.getElementById("profile-link").style.display = "flex";
    } else {
        document.getElementById("login-link").style.display = "flex";
        document.getElementById("profile-link").style.display = "none";
    }
});
