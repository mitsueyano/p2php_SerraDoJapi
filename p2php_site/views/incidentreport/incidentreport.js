//Ao carregar a página, rola a janela para baixo até a altura do header
window.addEventListener("load", () => {
  const header = document.getElementById("header");
  window.scrollTo({
    top: header.offsetHeight,
  });
});

//Quando o checkbox muda de estado
const checkbox = document.getElementById("identified");
const items = document.querySelectorAll(".items");
checkbox.addEventListener("change", () => {
  if (checkbox.checked) {
    items.forEach((item) => {
      item.readonly = true;
      item.style.opacity = "1";
      item.setAttribute("disabled", true);
      item.value = "";
    });
  } else {
    //Se desmarcado, habilita os campos, restaura estilos e permite edição
    items.forEach((item) => {
      item.style.color = "#000";
      item.style.backgroundColor = "";
      item.style.border = "1px solid grey";
      item.style.opacity = "";
      item.removeAttribute("disabled");
    });
  }
});

document.addEventListener("DOMContentLoaded", function () {
  const buttons = document.querySelectorAll("#options button");
  const animalFields = document.getElementById("animal-fields");
  const imageField = document.getElementById("image");
  const incidentType = document.getElementById("incident_type");
 
  buttons.forEach((button) => { //Adiciona evento de clique em cada botão
    button.addEventListener("click", () => {
      buttons.forEach((btn) => btn.classList.remove("active"));
      button.classList.add("active");

      if (button.value === "animal") { //Se selecionado "animal", mostra os campos relacionados e torna obrigatórios
        incidentType.value = "animal";
        animalFields.style.display = "flex";
        imageField.style.marginBottom = "5px";
        animalFields.querySelectorAll("input, select").forEach((input) => {
          input.required = true;
        });
      } else { //Se selecionado "ambiental", esconde os campos e remove obrigatoriedade
        incidentType.value = "ambiental";
        animalFields.style.display = "none";
        imageField.style.marginBottom = "10%";
        animalFields.querySelectorAll("input, select").forEach((input) => {
          input.required = false;
        });
      }
    });
  });
});

//Função para disparar clique no input de imagem
const upload = () => {
  document.querySelector("#image").click();
};

//Função para mostrar preview da imagem selecionada pelo usuário
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

imageInput.addEventListener("change", previewImage);

document.getElementById("share").addEventListener("click", () => {
  if (!imageInput.files.length) {
    alert("Por favor, selecione uma imagem.");
    return;
  }
  form.requestSubmit();
});
