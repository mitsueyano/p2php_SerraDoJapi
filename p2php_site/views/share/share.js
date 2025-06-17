window.addEventListener("load", function () {
  const header = document.getElementById("header");
  window.scrollTo({
    top: header.offsetHeight,
    behavior: "smooth",
  });
});

const checkbox = document.getElementById("identified");
const items = document.querySelectorAll(".items");
const checks = document.querySelectorAll(".checks");
 
checkbox.addEventListener("change", () => { //Checkbox que identifica se o organismo já é identificado
  if (checkbox.checked) {
    items.forEach((item) => {
      item.value = "";
      item.readonly = true;
      item.style.color = "linen";
      item.style.opacity = "1";
      item.setAttribute("disabled", true);
    });
    checks.forEach((check) => {
      check.readonly = true;
      check.disabled = true;
    });
  } else {
    items.forEach((item) => {
      item.style.color = "#000";
      item.style.backgroundColor = "";
      item.style.border = "1px solid grey";
      item.style.opacity = "";
      item.removeAttribute("disabled");
    });
    checks.forEach((check) => {
      check.readonly = false;
      check.disabled = false;
    });
  }
});

//Checkbox para marcar como "espécie invasora"
const invaderCheckbox = document.getElementById("invader");
const categoryInputs = document.querySelectorAll("input[name='category']");
let lastSelectedCategory = null;

invaderCheckbox.addEventListener("change", () => {
  const disabled = invaderCheckbox.checked;

  if (disabled) {
    categoryInputs.forEach((input) => {
      if (input.checked) lastSelectedCategory = input.value;
      input.checked = false;
      input.disabled = true;
    });
  } else {
    categoryInputs.forEach((input, index) => {
      input.disabled = false;
      if (lastSelectedCategory && input.value === lastSelectedCategory) {
        input.checked = true;
      } else if (!lastSelectedCategory && index === 0) {
        input.checked = true;
      }
    });
  }
});

//Função para simular o clique no input de imagem
const upload = () => {
  document.querySelector("#image").click();
};

// Exibe a imagem selecionada no preview
function previewImage(event) {
  const input = event.target;
  const preview = document.getElementById("image-preview");

  if (input.files && input.files[0]) {
    const fileURL = URL.createObjectURL(input.files[0]);
    preview.style.backgroundImage = "url(" + fileURL + ")";
    preview.classList.remove("hidden");
  }
}

const form = document.getElementById("form");
const imageInput = document.getElementById("image");

document.getElementById("share").addEventListener("click", () => {
  if (!imageInput.files.length) {
    alert("Por favor, selecione uma imagem.");
    return;
  }
  form.requestSubmit();
});
