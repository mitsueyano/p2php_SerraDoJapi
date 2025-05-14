<?php
    session_start();

    if (!isset($_SESSION["logado"]) || $_SESSION["logado"] != TRUE) {
        header("Location: ../login/login.php");
    }   
    else {
        echo "<h1>Seja bem-vindo, ".$_SESSION["user"]."</h1>";
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Compartilhar observação</title>
        <link rel="stylesheet" href="../padroes/padraoPag.css">
        <link rel="stylesheet" href="compartilhar.css">
        <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
        <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    </head>
    <body>
        <div id="header">
            <h1>ECOFRAME</h1>
            <span>Foto: José Aparecido dos Santos</span>
        </div>
        <div id="navbar">
            <a href="../index/index.php">INÍCIO</a>
            <a href="../explorar/explorar.php" class="selected">EXPLORAR</a>
            <a href="../login/login.php" id="login-link">ENTRE / CADASTRE-SE</a>
            <a href="../perfil/perfil.php" id="perfil-link" style="display: none;">PERFIL</a>
        </div>
        <div id="conteudo">
            <div id="caixa">
                <form action="../../php_funcoes/upload.php" method="POST" enctype="multipart/form-data">
                    <label for="imagem" class="texto">Selecione uma imagem:</label><br>
                    <input type="file" name="imagem" accept="image/*" required><br><br>
                    <div class="flex">
                        <label for="nomepopular">Nome Popular:</label>
                        <input type="text" name="nomepopular" required>
                    </div>
                    <label for="classtaxonomica" class="texto">Classificação Taxonomica:</label>
                    <div class="flex"><label for="classe">Classe:</label><input type="text" name="classe" required></div>   
                    <div class="flex"><label for="familia">Família:</label><input type="text" name="familia" required></div>
                    <div class="flex"><label for="especie">Espécie:</label><input type="text" name="especie" required></div>
                    <div class="flex"><label for="data">Data:</label><input type="date" name="data" required></div>
                    <div class="flex"><label for="hora">Hora:</label><input type="time" name="hora" required></div>
                    <label for="comentario">Comentário</label><textarea name="comentario"></textarea>
                    <label for="geolocalizacao">Geolocalização:</label><br>
                    <div id="map" style="height: 400px; width: 100%;"></div>
                    
                    <div class="flex">
                        <label for="latitude">Latitude:</label>
                        <input type="text" name="latitude" id="latitude" readonly required><br>
                    </div>
                    <div class="flex">
                        <label for="longitude">Longitude:</label>
                        <input type="text" name="longitude" id="longitude" readonly required><br>
                    </div>
                    <input type="submit" value="Enviar imagem">
                </form>
            </div>
        </div>
    </body>
    <script>
        sessionStorage.setItem("logado", "<?php echo isset($_SESSION['logado']) && $_SESSION['logado'] === true ? 'true' : 'false'; ?>");
    </script>
    <script src="../padroes/mostraPerfil.js"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
    <script src="compartilhar.js"></script>
</html>
