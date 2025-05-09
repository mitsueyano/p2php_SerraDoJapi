const simRadio = document.getElementById('sim');
const naoRadio = document.getElementById('nao');
const campoLattes = document.getElementById('campoLattes');

function atualizarCampoLattes() {
    campoLattes.style.display = simRadio.checked ? 'block' : 'none';
}

simRadio.addEventListener('change', atualizarCampoLattes);
naoRadio.addEventListener('change', atualizarCampoLattes);

atualizarCampoLattes();