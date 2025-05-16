//Inicializa o mapa em Jundiaí
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



async function buscarTaxonomia() {

    var entradaUsuario = document.getElementById("nomepopular").value;
    var inputNomeCientifico = document.getElementById("nomecientifico");
    var inputClasse =  document.getElementById("classe");
    var inputFamilia =  document.getElementById("familia");
    var inputEspecie =  document.getElementById("especie");

  try {
    //iNaturalist

    const iNatResponse = await fetch(`https://api.inaturalist.org/v1/taxa?q=${encodeURIComponent(entradaUsuario)}&locale=pt-BR&per_page=1`);
    const iNatData = await iNatResponse.json();

    if (!iNatData.results.length) {
      console.log("⚠️ Espécie não encontrada no iNaturalist.");
      inputNomeCientifico.value = "";
      inputClasse.value = "";
      inputFamilia.value = "";
      inputEspecie.value = "";
      return;
    }

    const nome = iNatData.results[0];
    const nomePopularOficial = nome.preferred_common_name || entradaUsuario;
    var nomeCientifico = nome.name;
   

    console.log("Entrada do usuário:", entradaUsuario);
    console.log("Nome popular reconhecido:", nomePopularOficial);
    console.log("Nome científico:", nomeCientifico);

    //Gbif                      
    const gbifResponse = await fetch(`https://api.gbif.org/v1/species/match?name=${encodeURIComponent(nomeCientifico)}`);
    const gbifData = await gbifResponse.json();

    if (!nomeCientifico) {
    console.log("Classificação taxonômica não encontrada no GBIF.");
    inputNomeCientifico.value = "";
      inputClasse.value = "";
      inputFamilia.value = "";
      inputEspecie.value = "";
      return;
    }
    
    var classe = gbifData.class;
    var familia = gbifData.family;
    var especie = gbifData.species;

    console.log("=====Classificação taxonômica simples=====");
    console.log("Reino:", gbifData.kingdom || "—");
    console.log("Filo:", gbifData.phylum || "—");
    console.log("Classe:", gbifData.class || "—"); 

    if (nomeCientifico != undefined){
       inputNomeCientifico.value = nomeCientifico;
    } else{
        inputNomeCientifico.value = "";
    }
    if (classe != undefined){
       inputClasse.value = classe;
    } else{
        inputClasse.value = "";
    }
    if (familia != undefined){
       inputFamilia.value = familia;
    } else{
        inputFamilia.value = "";
    }
    if (especie != undefined){
       inputEspecie.value = especie;
    } else{
        inputEspecie.value = "";
    }

  } catch (erro) {
    console.error("❌ Erro ao buscar dados:", erro);
  }
}




