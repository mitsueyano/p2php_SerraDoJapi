window.addEventListener("load", function () {
    const header = document.getElementById("header");
    window.scrollTo({
        top: header.offsetHeight,
        behavior: "smooth"
    });
});