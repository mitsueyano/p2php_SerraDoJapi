window.addEventListener("load", function () {
  const header = document.getElementById("header");
  window.scrollTo({
    top: header.offsetHeight,
    behavior: "smooth",
  });
});

const checkbox = document.getElementById("identified");
const items = document.querySelectorAll(".items");

checkbox.addEventListener("change", () => {
  if (checkbox.checked) {
    items.forEach((item) => {
      item.readonly = true;
      item.style.color = "linen";
      item.style.opacity = "1";
      item.setAttribute("disabled", true);
    });
  } else {
    items.forEach((item) => {
      item.style.backgroundColor = "";
      item.style.border = "1px solid grey";
      item.style.opacity = "";
      item.removeAttribute("disabled");
    });
  }
});

const upload = () => {
  document.querySelector("#image").click();
};

function previewImage(event) {
  const input = event.target;
  const preview = document.getElementById("image-preview");

  if (input.files && input.files[0]) {
    const fileURL = URL.createObjectURL(input.files[0]);
    preview.src = fileURL;
    preview.classList.remove("hidden");
  }
}

const form = document.getElementById("form");
const imageInput = document.getElementById("image");

imageInput.addEventListener("change", previewImage);

document.getElementById("share").addEventListener("click", () => {
  if (!imageInput.files.length) {
    alert("Por favor, selecione uma imagem.");
    return;
  }
  form.requestSubmit();
});
