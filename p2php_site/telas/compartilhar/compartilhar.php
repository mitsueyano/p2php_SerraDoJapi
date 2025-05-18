<!DOCTYPE html>
<?php
    session_start();
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compartilhar observação</title>
    <link rel="stylesheet" href="../padroes/padraoPag.css">
    <link rel="stylesheet" href="compartilhar.css">
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
        <a href="../explorar/explorar.php" class="selected">EXPLORAR</a>
        <a href="../login/login.php" id="login-link">ENTRE / CADASTRE-SE</a>
        <a href="../perfil/perfil.php" id="perfil-link" style="display: none;">PERFIL</a>
    </div>
    <div id="conteudo">
        <div id="caixa">
            <form action="../../php_funcoes/upload.php" method="POST" enctype="multipart/form-data">

                <div class="info">
                    <span class="texto">Selecione uma imagem:</span><br>
                    <input type="file" name="imagem" accept="image/*" id="imagem" required><br><br>
                    <div class="flex">
                        <label for="nomepopular">Nome Popular:</label>
                        <input type="text" name="nomepopular" id="nomepopular" autocomplete="off" required>
                        <div id="dropdown-list" class="dropdown-list"></div>
                    </div>
                   
                    <br>
                    <div class="flex">
                        <label for="nomecientifico">Nome Científico:</label>
                        <input type="text" name="nomecientifico" id="nomecientifico" required>
                    </div>
                    <span class="texto">Classificação Taxonomica:</span>
                    <div class="flex">
                        <label for="classe">Classe:</label><input type="text" name="classe" id="classe" required>
                    </div>
                    <div class="flex">
                        <label for="ordem">Ordem:</label>
                        <input type="text" name="ordem" id="ordem" required>
                    </div>
                    <div class="flex">
                        <label for="familia">Família:</label><input type="text" name="familia" id="familia" required>
                    </div>
                    <div class="flex">
                        <label for="data">Data:</label><input type="date" name="data" id="data" required>
                    </div>
                    <div class="flex">
                        <label for="hora">Hora:</label><input type="time" name="hora" id="hora" required>
                    </div>
                    <label for="comentario">Comentário</label><textarea name="comentario"
                        id="comentario"></textarea><br>
                </div>
                <div class="geo">
                    <span>Geolocalização:</span>
                    <div id="map" style="height: 400px; width: 100%;"></div>
                    <div class="flexlugar">
                        <label for="nomelugar">Nome do lugar / Referência:</label>
                        <input type="text" name="nomelugar" id="nomelugar" required>
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
                    <input type="submit" value="Compartilhar" id="compartilhar">
                </div>
            </form>
        </div>
        <span>Quer comunicar algo? <a href="../regOcorrencia/regOcorrencia.php">Registre uma Ocorrência</a>.</span>
        
    </div>
</body>
<script>
    sessionStorage.setItem("logado", "<?php echo isset($_SESSION['logado']) && $_SESSION['logado'] === true ? 'true' : 'false'; ?>");
</script>
<script src="https://kit.fontawesome.com/c68ccb89e7.js" crossorigin="anonymous"></script>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
<script src="compartilhar.js"></script>
<script src="../padroes/mostraPerfil.js"></script>


</html>