<!DOCTYPE html>
<?php
    session_start();
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compartilhar observação</title>
    <link rel="stylesheet" href="../padroes/padraoPag.css">
    <link rel="stylesheet" href="registrarOcorrencia.css">
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
            <form action="../../php_funcoes/uploadOcorrencia.php" method="POST" enctype="multipart/form-data">

                <div class="info">
                    <span class="texto">Selecione uma imagem:</span><br>
                    <input type="file" name="imagem" accept="image/*" id="imagem" required><br><br>
                    <div id="datahora">
                        <div class="flex">
                            <label for="data">Data:</label><input type="date" name="data" id="data" required>
                        </div>
                        <div class="flex">
                            <label for="hora">Hora:</label><input type="time" name="hora" id="hora" required>
                        </div>
                    </div>
                    <label for="titulo">Título:</label><input type="text" name="titulo" id="titulo" required>
                    <label for="descricao">Descrição</label><textarea name="descricao"
                        id="descricao"></textarea><br>
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
                   <input type="submit" value="Registrar" id="registrar">
                </div>
            </form>
        </div>
    </div>
</body>
<script>
    sessionStorage.setItem("logado", "<?php echo isset($_SESSION['logado']) && $_SESSION['logado'] === true ? 'true' : 'false'; ?>");
</script>
<script src="https://kit.fontawesome.com/c68ccb89e7.js" crossorigin="anonymous"></script>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
<script src="registrarOcorrencia.js"></script>
<script src="../padroes/mostraPerfil.js"></script>


</html>