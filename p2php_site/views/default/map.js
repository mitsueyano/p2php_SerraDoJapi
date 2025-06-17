const map = L.map('map').setView([-23.1857, -46.8978], 13); //Inicializa o mapa em Jundiaí

//Define o provedor de tiles (os quadradinhos) como OpenStreetMap e adiciona ao mapa
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
}).addTo(map);

let marker; //Marcador (será criado quando o usuário clicar no mapa)

function formatAddress(addr, fallbackName = "") {
    const pontoReferencia = addr.amenity || addr.shop || addr.tourism || addr.building || "";
    const street = addr.road || addr.pedestrian || addr.footway || addr.cycleway || addr.path || "";
    const number = addr.house_number || "";
    const neighbourhood = addr.neighbourhood || addr.suburb || "";
    const city = addr.city || addr.town || addr.village || "";

    let segments = [];

    if (pontoReferencia) segments.push(pontoReferencia);
    if (street) segments.push(street);
    if (number) segments.push(number);
    if (neighbourhood) segments.push(neighbourhood);
    if (city) segments.push(city);

    return segments.join(", ") || fallbackName;
}

/* Preenche automaticamente o campo de nome do local (placename) com base nas coordenadas & Utiliza a API de geocodificação reversa do OpenStreetMap (Nominatim). */
function fillPlaceName(latlng) {
    const nameField = document.getElementById('placename');

    const url = `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${latlng.lat}&lon=${latlng.lng}`;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data && data.address) {
                nameField.value = formatAddress(data.address, data.display_name || "");
            } else {
                nameField.value = "";
            }
        })
        .catch(err => {
            console.error("Erro ao buscar nome do lugar:", err);
            nameField.value = "";
        });
}

//Adiciona um controle de busca (geocoder) ao mapa
L.Control.geocoder({
    defaultMarkGeocode: false //Evita adicionar marcador automático
})
.on('markgeocode', function(e) {
    const latlng = e.geocode.center;
    map.setView(latlng, 15); //Centraliza o mapa na localização geocodificada

    //Cria ou atualiza o marcador
    if (marker) {
        marker.setLatLng(latlng);
    } else {
        marker = L.marker(latlng).addTo(map);
    }

    //Preenche os campos de latitude e longitude no formulário
    document.getElementById('latitude').value = latlng.lat.toFixed(6);
    document.getElementById('longitude').value = latlng.lng.toFixed(6);

    //Tenta montar o nome do local a partir da resposta do Geocoder
    let placename = "";

    if (e.geocode.properties && e.geocode.properties.address) {
        placename = formatAddress(e.geocode.properties.address, e.geocode.name || "");
    } else {
        placename = e.geocode.name || "";
    }

    document.getElementById('placename').value = placename; //Preenche o campo de nome do local no formulário
})
.addTo(map);

//Função do click
map.on('click', function (e) {
    const { lat, lng } = e.latlng;

    if (marker) {
        marker.setLatLng(e.latlng);
    } else {
        marker = L.marker(e.latlng).addTo(map);
    }

    document.getElementById('latitude').value = lat.toFixed(6);
    document.getElementById('longitude').value = lng.toFixed(6);

    fillPlaceName(e.latlng);
});
