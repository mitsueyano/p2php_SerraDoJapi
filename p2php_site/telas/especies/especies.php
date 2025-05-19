<?php
session_start();
include('../../php_funcoes/conectaBD.php');

$tipoSelecionado = isset($_GET['tipo']) ? $_GET['tipo'] : 'todos';
$tiposValidos = ['todos', 'animais', 'insetos', 'plantas'];
if (!in_array($tipoSelecionado, $tiposValidos)) {
    $tipoSelecionado = 'todos';
}

// Monta a query com filtro:
if ($tipoSelecionado == 'todos') {
    $query = "SELECT especie, tipo FROM classificacao_taxonomica ORDER BY especie";
} else {
    $query = "SELECT especie, tipo FROM classificacao_taxonomica WHERE tipo = ? ORDER BY especie";
}

if ($tipoSelecionado == 'todos') {
    $resultado = $conexao->query($query);
} else {
    $stmt = $conexao->prepare($query);
    $stmt->bind_param("s", $tipoSelecionado);
    $stmt->execute();
    $resultado = $stmt->get_result();
}

$especies = [];
while ($row = $resultado->fetch_assoc()) {
    $letra = strtoupper($row['especie'][0]);
    $especies[$letra][] = $row['especie'];
}

$letras = range('A', 'Z');
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Espécies</title>
    <link rel="stylesheet" href="../padroes/padraoPag.css" />
    <link rel="stylesheet" href="especies.css" />
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
        <a href="../perfil/perfil.php" id="perfil-link" style="display:none;">PERFIL</a>
    </div>
    <div id="conteudo">
        <span id="titulo">ESPÉCIES REGISTRADAS</span>
        <div id="barra">
            <div class="flex" id="filtro">
                <button onclick="carregarEspecies('todos')" id="btn-todos" class="ativo">Todos</button>
                <button onclick="carregarEspecies('animais')" id="btn-animais">Animais</button>
                <button onclick="carregarEspecies('insetos')" id="btn-insetos">Insetos</button>
                <button onclick="carregarEspecies('plantas')" id="btn-plantas">Plantas</button>

            </div>
        </div>
        <div id="caixa">
            <div id="lista-especies">
                <div id="navbar-letras">
                    <?php foreach ($letras as $letra): ?>
                        <a href="#marcador-letra-<?php echo $letra; ?>"><?php echo $letra; ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
            <div id="div-info-especie"></div>
        </div>
    </div>
    <script src="../padroes/mostraPerfil.js"></script>
    <script src="especies.js"></script>
    <script>
        sessionStorage.setItem("logado", "<?php echo isset($_SESSION['logado']) && $_SESSION['logado'] === true ? 'true' : 'false'; ?>");
    </script>
</body>

</html>