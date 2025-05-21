<!DOCTYPE html>
<?php
    session_start();
    if (isset($_SESSION ["loggedin"]) != "loggedin"){
        header("Location: ../login/login.php");
    }
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compartilhar observação</title>
    <link rel="stylesheet" href="../default/default.css">
    <link rel="stylesheet" href="incidentreport.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css"/>
</head>

<body>
    <div id="header">
        <h1>ecoframe</h1>
        <span>Foto: José Aparecido dos Santos</span>
    </div>
    <div id="navbar">
        <a href="../index/index.php">INÍCIO</a>
        <a href="../explore/explore.php" class="selected">EXPLORAR</a>
        <a href="../login/login.php" id="login-link">ENTRE / CADASTRE-SE</a>
        <a href="../profile/profile.php" id="profile-link" style="display: none;">PERFIL</a>
    </div>
    <div id="content">
        <div id="box">
            <form action="../../php/uploadIncident.php" method="POST" enctype="multipart/form-data">
                <div class="info">
                    <span class="text">Selecione uma imagem:</span><br>
                    <input type="file" name="image" accept="image/*" id="image" required><br>
                    <div class="flexcheck">
                        <input type="checkbox" name="notidentified" id="notidentified"><label for="notidentified">Não identificado</label>
                    </div>
                    <div class="flex">
                        <label for="common-name">Nome Popular:</label>
                        <input type="text" name="common-name" id="common-name" autocomplete="off" required>
                        <div id="dropdown-list" class="dropdown-list"></div>
                    </div>
                   
                    <br>
                    <div class="flex">
                        <label for="scientific-name">Nome Científico:</label>
                        <input type="text" name="scientific-name" id="scientific-name" required>
                    </div>
                    <span class="texto">Classificação Taxonomica:</span>
                    <div class="flex">
                        <label for="class">Classe:</label><input type="text" name="class" id="class" required>
                    </div>
                    <div class="flex">
                        <label for="order">Ordem:</label>
                        <input type="text" name="order" id="order" required>
                    </div>
                    <div class="flex">
                        <label for="family">Família:</label><input type="text" name="family" id="family" required>
                    </div>
                    <div class="flex">
                        <label for="date">Data:</label><input type="date" name="date" id="date" required>
                    </div>
                    <div class="flex">
                        <label for="time">Hora:</label><input type="time" name="time" id="time" required>
                    </div>
                    <label for="description">Descrição</label><textarea name="description"
                        id="description"></textarea><br>
                </div>
                <div class="geo">
                    <span>Geolocalização:</span>
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
                    <br><br>
                    <input type="submit" value="Registrar" id="share">
                </div>
            </form>
        </div>
    </div>
</body>
<script>
    sessionStorage.setItem("loggedin", "<?php echo isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true ? 'true' : 'false'; ?>");
</script>
<script src="https://kit.fontawesome.com/c68ccb89e7.js" crossorigin="anonymous"></script>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
<script src="incidentreport.js"></script>
<script src="../default/showprofile.js"></script>
<script src="../default/map.js"></script>
<script src="../default/speciesAPI.js"></script>


</html>