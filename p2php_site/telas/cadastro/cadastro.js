//Formatar CPF enquanto digita
document.querySelector('input[name="cpf"]').addEventListener('input', function (e) {
    let value = e.target.value.replace(/\D/g, ""); // remove tudo que não é número

    if (value.length > 11) value = value.slice(0, 11);

    value = value.replace(/(\d{3})(\d)/, "$1.$2");
    value = value.replace(/(\d{3})(\d)/, "$1.$2");
    value = value.replace(/(\d{3})(\d{1,2})$/, "$1-$2");

    e.target.value = value;
});

//Valida o CPF
document.querySelector('input[name="cpf"]').addEventListener('blur', function (e) {
    const cpf = e.target.value.replace(/\D/g, "");
    const cpfErrorSpan = document.querySelector('#cpfError');
    if (!validarCPF(cpf)) {
        //Cria/Exibe Mensagem de erro
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
        //Esconde a mensagem de erro
        if (cpfErrorSpan) {
            cpfErrorSpan.style.display = 'none';
        }
    }
});
function validarCPF(cpf) {
    if (cpf.length !== 11 || /^(\d)\1{10}$/.test(cpf)) return false;

    let soma = 0;
    for (let i = 0; i < 9; i++) soma += parseInt(cpf.charAt(i)) * (10 - i);
    let resto = (soma * 10) % 11;
    if (resto === 10 || resto === 11) resto = 0;
    if (resto !== parseInt(cpf.charAt(9))) return false;

    soma = 0;
    for (let i = 0; i < 10; i++) soma += parseInt(cpf.charAt(i)) * (11 - i);
    resto = (soma * 10) % 11;
    if (resto === 10 || resto === 11) resto = 0;
    return resto === parseInt(cpf.charAt(10));
}

//Verifica se as senhas são iguais
document.querySelector("form").addEventListener("submit", function (e) {
    const senha = document.querySelector('input[name="senha"]').value;
    const senha2 = document.querySelector('input[name="senha2"]').value;

    if (senha !== senha2) {
        alert("As senhas não coincidem!");
        e.preventDefault();
    }
});

//Exibição do campo lattes
const simRadio = document.getElementById('sim');
const naoRadio = document.getElementById('nao');
const campoLattes = document.getElementById('campoLattes');

function atualizarCampoLattes() {
    campoLattes.style.display = simRadio.checked ? 'block' : 'none';
}

simRadio.addEventListener('change', atualizarCampoLattes);
naoRadio.addEventListener('change', atualizarCampoLattes);

atualizarCampoLattes();