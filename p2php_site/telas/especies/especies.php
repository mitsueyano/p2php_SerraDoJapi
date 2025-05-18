<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espécies</title>
    <link rel="stylesheet" href="../padroes/padraoPag.css">
    <link rel="stylesheet" href="especies.css">
</head>

<body>
    <div id="header">
        <h1>ecoframe</h1>
        <span>Foto: José Aparecido dos Santos</span>
    </div>
    <div id="navbar">
        <a href="../index/index.php" class="selected">INÍCIO</a>
        <a href="../explorar/explorar.php">EXPLORAR</a>
        <a href="../login/login.php" id="login-link">ENTRE / CADASTRE-SE</a>
        <a href="../perfil/perfil.php" id="perfil-link" style="display: none;">PERFIL</a>
    </div>
    <div id="conteudo">
        <span id="titulo">ESPÉCIES REGISTRADAS</span>
        <div id="barra">
            <div class="flex" id="filtro">
                <button data-filtro="todos" class="ativo">Todos</button>
                <button data-filtro="fauna">Fauna</button>
                <button data-filtro="flora">Flora</button>
            </div>
        </div>  
        <div id="caixa">
            <?php
            include('../../php_funcoes/conectaBD.php');
            $query = "SELECT especie FROM classificacao_taxonomica ORDER BY especie";
            $resultado = $conexao->query($query);

            $especies = [];
            while ($row = $resultado->fetch_assoc()) {
                $letra = strtoupper($row['especie'][0]);
                $especies[$letra][] = $row['especie'];
            }

            $letras = range('A', 'Z');
            ?>
            <div id="lista-especies">
                <div id="navbar-letras">
                    <?php foreach ($letras as $letra): ?>
                        <a href="#letra-<?php echo $letra; ?>"><?php echo $letra; ?></a>
                    <?php endforeach; ?>
                </div>

                <?php foreach ($letras as $letra): ?>
                    <?php if (isset($especies[$letra])): ?>
                        <h3 id="letra-<?php echo $letra; ?>"><?php echo $letra; ?></h3>
                        <?php foreach ($especies[$letra] as $nome): ?>
                            <div class="especie-item" onclick="carregarEspecie('<?php echo addslashes($nome); ?>')">
                                <?php echo htmlspecialchars($nome); ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <div id="div-info-especie">

            </div>
        </div>
    </div>
</body>

</html>
<script src="../padroes/mostraPerfil.js"></script>
<script src="especies.js"></script>
<script>
    sessionStorage.setItem("logado", "<?php echo isset($_SESSION['logado']) && $_SESSION['logado'] === true ? 'true' : 'false'; ?>");
</script>
