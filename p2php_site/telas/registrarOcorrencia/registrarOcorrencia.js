// Inicializa o mapa em Jundiaí
const map = L.map('map').setView([-23.1857, -46.8978], 13);

// Adiciona o tile layer do OpenStreetMap
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
}).addTo(map);

let marker;

// Função para buscar endereço pelo latlng e preencher o campo nomelugar
function preencherNomeLugar(latlng) {
    const campoNome = document.getElementById('nomelugar');

    const url = `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${latlng.lat}&lon=${latlng.lng}`;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data && data.address) {
                const addr = data.address;

                const pontoReferencia = addr.amenity || addr.shop || addr.tourism || addr.building || "";
                const rua = addr.road || addr.pedestrian || addr.footway || addr.cycleway || addr.path || "";
                const bairro = addr.neighbourhood || addr.suburb || "";
                const cidade = addr.city || addr.town || addr.village || "";

                let partes = [];

                if (pontoReferencia) partes.push(pontoReferencia);
                if (rua) partes.push(rua);
                if (bairro) partes.push(bairro);
                if (cidade) partes.push(cidade);
                

                campoNome.value = partes.join(", ") || data.display_name || "";
            } else {
                campoNome.value = "";
            }
        })
        .catch(err => {
            console.error("Erro ao buscar nome do lugar:", err);
            campoNome.value = "";
        });
}


// Geocoder (barra de pesquisa)
L.Control.geocoder({
    defaultMarkGeocode: false
})
.on('markgeocode', function(e) {
    const latlng = e.geocode.center;
    map.setView(latlng, 15);

    if (marker) {
        marker.setLatLng(latlng);
    } else {
        marker = L.marker(latlng).addTo(map);
    }

    document.getElementById('latitude').value = latlng.lat.toFixed(6);
    document.getElementById('longitude').value = latlng.lng.toFixed(6);

    let nomeLugar = "";

    if (e.geocode.properties && e.geocode.properties.address) {
        const addr = e.geocode.properties.address;

        const rua = addr.road || addr.pedestrian || addr.footway || addr.cycleway || addr.path || "";
        const numero = addr.house_number || "";
        const cidade = addr.city || addr.town || addr.village || "";

        let partes = [];
        if (rua) partes.push(rua);
        if (numero) partes.push(numero);
        if (cidade) partes.push(cidade);


        nomeLugar = partes.join(", ") || e.geocode.name || "";
    } else {
        nomeLugar = e.geocode.name || "";
    }

    document.getElementById('nomelugar').value = nomeLugar;
})
.addTo(map);

// Ao clicar no mapa preenche latitude, longitude e busca o nome do lugar (se nomelugar vazio)
map.on('click', function (e) {
    const { lat, lng } = e.latlng;

    if (marker) {
        marker.setLatLng(e.latlng);
    } else {
        marker = L.marker(e.latlng).addTo(map);
    }

    document.getElementById('latitude').value = lat.toFixed(6);
    document.getElementById('longitude').value = lng.toFixed(6);

    preencherNomeLugar(e.latlng); // Já chama a função de geocodificação reversa
});



window.addEventListener("load", function () {
    const header = document.getElementById("header");
    window.scrollTo({
        top: header.offsetHeight,
        behavior: "smooth"
    });
});