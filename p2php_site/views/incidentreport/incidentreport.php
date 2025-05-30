<!DOCTYPE html>
<?php
session_start();
if (isset($_SESSION["loggedin"]) != "loggedin") {
    header("Location: ../login/login.php");
}
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de ocorrências</title>
    <link rel="stylesheet" href="../default/default.css">
    <link rel="stylesheet" href="incidentreport.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
</head>

<body>
    <div id="header">
        <h1>ecoframe</h1>
        <span>Foto: José Aparecido dos Santos</span>
    </div>
    <div id="navbar">
        <a href="../index/index.php">INÍCIO</a>
        <a href="../explore/explore.php" class="selected">EXPLORAR</a>
        <?php
        if (isset($_SESSION['loggedin'])) {
            echo '<a href="../profile/profile.php?username=' . $_SESSION['username'] . '" id="profile-link">PERFIL</a>';
        } else {
            echo '<a href="../login/login.php" id="login-link">ENTRE</a>';
        }
        ?>
    </div>
    <div id="content">
        <div id="box">
            <form action="../../php/uploadIncident.php" method="POST" enctype="multipart/form-data" id="form">
                <div class="image-pick">
                    <span class="text">Tipo de ocorrência</span>
                    <div id="options">
                        <button type="button" value="animal" class="active">Animal</button>
                        <button type="button" value="environmental">Ambiental</button>
                        <input type="text" name="incident_type" id="incident_type" hidden>
                    </div>
                    <span class="text">Selecione uma imagem:</span>
                    <div id="div-image-selection" onclick="upload()">
                        <div id="image-overlay">
                            <span id="image-overlay-text">Clique para selecionar uma imagem</span>
                        </div>
                        <div id="image-selected">
                            <div id="image-preview" class="hidden"></div>
                        </div>

                    </div>
                    <input type="file" name="image" accept="image/*" id="image" hidden required
                        onchange="previewImage(event)">
                </div>
                <div class="info">
                    <span class="text">Informações gerais</span>
                    <div id="animal-fields">
                        <div class="flexcheck">
                            <div>
                                <input type="checkbox" name="identified" id="identified"><label for="identified">Espécie não identificada</label>
                            </div>
                            <div>
                                <input type="checkbox" name="invader" id="invader"><label for="invader">Espécie invasora</label>
                            </div>
                        </div>
                        <div id="flexradio">
                            <div>
                                <input class="items" type="radio" name="category" id="fauna" value="Fauna" required>
                                <label for="fauna">Fauna</label>
                            </div>
                            <div>
                                <input class="items" type="radio" name="category" id="flora" value="Flora">
                                <label for="flora">Flora</label>
                            </div>
                        </div>
                        <div class="flex">
                            <label for="common-name">Nome Popular:</label>
                            <input type="text" class="items" name="common-name" id="common-name" autocomplete="off"
                                required>
                            <div id="dropdown-list" class="dropdown-list"></div>
                        </div>
                        <br>
                        <div class="flex">
                            <label for="scientific-name">Nome Científico:</label>
                            <input type="text" class="items" name="scientific-name" id="scientific-name" required>
                        </div>
                        <span class="texto">Classificação Taxonômica:</span>
                        <div class="flex">
                            <label for="class">Classe:</label>
                            <input type="text" class="items" name="class" id="class" required>
                        </div>
                        <div class="flex">
                            <label for="order">Ordem:</label>
                            <input type="text" class="items" name="order" id="order" required>
                        </div>
                        <div class="flex">
                            <label for="family">Família:</label>
                            <input type="text" class="items" name="family" id="family" required>
                        </div>
                    </div>
                    <div id="flexdatetime">
                        <div class="flex">
                            <label for="date">Data:</label><input type="date" name="date" id="date" required>
                        </div>
                        <div class="flex">
                            <label for="time">Hora:</label><input type="time" name="time" id="time" required>
                        </div>
                    </div>

                    <div class="flextitle"><label for="incident_title">Título:</label><input type="text"
                            name="incident_title" id="incidentTitle" required></div>
                    <label for="description">Descrição</label><textarea name="description" id="description"></textarea>
                </div>
                <div class="geo">
                    <span class="text">Geolocalização:</span>
                    <div id="map" style="height: 400px; width: 100%;"></div>
                    <div class="flexplace">
                        <label for="placename">Nome do lugar / Referência:</label>
                        <input type="text" name="placename" id="placename" required>
                    </div>
                    <br>
                    <div class="flex">
                        <label for="latitude">Latitude:</label>
                        <input type="text" name="latitude" id="latitude" readonly required>
                    </div>
                    <div class="flex">
                        <label for="longitude">Longitude:</label>
                        <input type="text" name="longitude" id="longitude" readonly required>
                    </div>
                    <br>
                </div>
            </form>
        </div>
        <input type="submit" value="Registrar" id="share">
    </div>
</body>
<script src="https://kit.fontawesome.com/c68ccb89e7.js" crossorigin="anonymous"></script>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
<script src="incidentreport.js"></script>
<script src="../default/map.js"></script>
<script src="../default/speciesAPI.js"></script>


</html>