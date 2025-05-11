//Inicializa o mapa em Jundiaí-SP
const map = L.map('map').setView([-23.1857, -46.8978], 13);

// Adiciona o tile layer (a camada base do mapa) do OpenStreetMap
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
}).addTo(map);

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
})
.addTo(map);

let marker;

//Ao clicar no mapa preenche os campos de latitude e longitude
map.on('click', function (e) {
    const { lat, lng } = e.latlng;

    if (marker) {
        marker.setLatLng(e.latlng);
    } else {
        marker = L.marker(e.latlng).addTo(map);
    }

    document.getElementById('latitude').value = lat.toFixed(6);
    document.getElementById('longitude').value = lng.toFixed(6);
});