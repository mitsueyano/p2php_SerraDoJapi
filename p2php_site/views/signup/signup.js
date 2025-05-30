var unTaken = true

//Formatar CPF enquanto digita
document.querySelector('input[name="cpf"]').addEventListener('input', function (e) {
    let value = e.target.value.replace(/\D/g, "");

    if (value.length > 11) value = value.slice(0, 11);

    value = value.replace(/(\d{3})(\d)/, "$1.$2");
    value = value.replace(/(\d{3})(\d)/, "$1.$2");
    value = value.replace(/(\d{3})(\d{1,2})$/, "$1-$2");

    e.target.value = value;
});

document.querySelector('input[name="cpf"]').addEventListener('blur', function (e) {
    const cpf = e.target.value.replace(/\D/g, "");
    const cpfErrorSpan = document.querySelector('#cpfError');
    if ( !cpfValidation(cpf)) {
        if (!cpfErrorSpan) {
            const errorSpan = document.createElement('span');
            errorSpan.id = 'cpfError';
            errorSpan.style.color = 'red';
            errorSpan.textContent = 'CPF inválido!';
            e.target.parentNode.appendChild(errorSpan);
        } else {
            cpfErrorSpan.style.display = 'inline';
        }
        e.target.focus();
    } else {
        if (cpfErrorSpan) {
            cpfErrorSpan.style.display = 'none';
        }
    }
});
function cpfValidation(cpf) {
    if (cpf.length !== 11 || /^(\d)\1{10}$/.test(cpf)) return false;

    let sum = 0;
    for (let i = 0; i < 9; i++) sum += parseInt(cpf.charAt(i)) * (10 - i);
    let remainder = (sum * 10) % 11;
    if (remainder === 10 || remainder === 11) remainder = 0;
    if (remainder !== parseInt(cpf.charAt(9))) return false;

    sum = 0;
    for (let i = 0; i < 10; i++) sum += parseInt(cpf.charAt(i)) * (11 - i);
    remainder = (sum * 10) % 11;
    if (remainder === 10 || remainder === 11) remainder = 0;
    return remainder === parseInt(cpf.charAt(10));
}

document.querySelector("form").addEventListener("submit", function (e) {
    const password = document.querySelector('input[name="password"]').value;
    const password2 = document.querySelector('input[name="password2"]').value;

    if (password !== password2) {
        alert("As senhas não coincidem!");
        e.preventDefault();
    }
    if (unTaken) {
        alert("Nome de usuário indisponível!");
        e.preventDefault();
    }
});

const yesRadio = document.getElementById('yes');
const noRadio = document.getElementById('no');
const Lattesfield = document.getElementById('lattesfield');

function updateLattesfield() {
    Lattesfield.style.display = yesRadio.checked ? 'block' : 'none';
}

yesRadio.addEventListener('change', updateLattesfield);
noRadio.addEventListener('change', updateLattesfield);

updateLattesfield();

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

document.addEventListener('DOMContentLoaded', function() {
    const usernameInput = document.getElementById('username');
    const usernameStatus = document.getElementById('username-status');
    let debounceTimer;
    
    // Debounce function
    function debounce(func, delay) {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(func, delay);
    }
    
    // Verifica disponibilidade do username
    function checkUsernameAvailability(username) {
        if (username.length < 3) {
            usernameStatus.className = 'username-status';
            return;
        }
        
        usernameStatus.className = 'username-status loading';
        
        fetch('../../php/checkusername.php?username=' + encodeURIComponent(username))
            .then(response => {
                if (!response.ok) throw new Error('Erro na verificação');
                return response.json();
            })
            .then(data => {
                if (data.available) {
                    usernameStatus.className = 'username-status available';
                    unTaken = false

                } else {
                    usernameStatus.className = 'username-status unavailable';
                    unTaken = true
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                usernameStatus.className = 'username-status error';
                unTaken = true
            });
    }
    
    // Event listener com debounce
    usernameInput.addEventListener('input', function() {
        const username = this.value.trim();
        debounce(() => checkUsernameAvailability(username), 500);
    });
});