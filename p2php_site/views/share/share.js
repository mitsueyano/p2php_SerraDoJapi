window.addEventListener("load", function () {
    const header = document.getElementById("header");
    window.scrollTo({
        top: header.offsetHeight,
        behavior: "smooth"
    });
});

const checkbox = document.getElementById('identified');
const items = document.querySelectorAll('.items');

checkbox.addEventListener('change', () => {
    if (checkbox.checked) {
        items.forEach(item => {
            item.readonly = true;
            item.style.color = "linen";
            item.style.opacity = "1";
            item.setAttribute('disabled', true);
        });
    } else {
        items.forEach(item => {
            item.style.backgroundColor = "";
            item.style.border = "1px solid grey";
            item.style.opacity = "";
            item.removeAttribute('disabled');
        });
    }
});
