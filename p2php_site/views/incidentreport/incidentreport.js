window.addEventListener("load", () => {
  const header = document.getElementById("header");
  window.scrollTo({
    top: header.offsetHeight,
  });
});

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

  buttons.forEach((button) => {
    button.addEventListener("click", () => {
      buttons.forEach((btn) => btn.classList.remove("active"));
      button.classList.add("active");

      if (button.value === "animal") {
        incidentType.value = "animal";
        animalFields.style.display = "flex";
        imageField.style.marginBottom = "5px";
        animalFields.querySelectorAll("input, select").forEach((input) => {
          input.required = true;
        });
      } else {
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
const upload = () => {
  document.querySelector("#image").click();
};

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
