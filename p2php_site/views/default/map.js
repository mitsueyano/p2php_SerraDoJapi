const map = L.map('map').setView([-23.1857, -46.8978], 13);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
}).addTo(map);

let marker;

function fillPlaceName(latlng) {
    const nameField = document.getElementById('placename');

    const url = `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${latlng.lat}&lon=${latlng.lng}`;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data && data.address) {
                const addr = data.address;

                const pontoReferencia = addr.amenity || addr.shop || addr.tourism || addr.building || "";
                const street = addr.road || addr.pedestrian || addr.footway || addr.cycleway || addr.path || "";
                const neighbourhood = addr.neighbourhood || addr.suburb || "";
                const city = addr.city || addr.town || addr.village || "";

                let segments = [];

                if (pontoReferencia) segments.push(pontoReferencia);
                if (street) segments.push(street);
                if (neighbourhood) segments.push(neighbourhood);
                if (city) segments.push(city);
                

                nameField.value = segments.join(", ") || data.display_name || "";
            } else {
                nameField.value = "";
            }
        })
        .catch(err => {
            console.error("Erro ao buscar nome do lugar:", err);
            nameField.value = "";
        });
}

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

    let placename = "";

    if (e.geocode.properties && e.geocode.properties.address) {
        const addr = e.geocode.properties.address;

        const street = addr.road || addr.pedestrian || addr.footway || addr.cycleway || addr.path || "";
        const number = addr.house_number || "";
        const city = addr.city || addr.town || addr.village || "";

        let segments = [];
        if (street) segments.push(street);
        if (number) segments.push(number);
        if (city) segments.push(city);


        placename = segments.join(", ") || e.geocode.name || "";
    } else {
        placename = e.geocode.name || "";
    }

    document.getElementById('placename').value = placename;
})
.addTo(map);

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