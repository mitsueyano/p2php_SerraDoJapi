<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explorar</title>
    <link rel="stylesheet" href="../padroes/padraoPag.css">
    <link rel="stylesheet" href="cadastro_login.css">
</head>
<body>
    <div id="header">
        <h1>ECOFRAME</h1>
        <span>Foto: José Aparecido dos Santos</span>
    </div>
    <div id="navbar">
        <a href="../index/index.php">INÍCIO</a>
        <a href="../explorar/explorar.php">EXPLORAR</a>
        <a href="../cadastro_login/cadastro_login.php" id="selected">ENTRE / CADASTRE-SE</a>
    </div>
    <div id="conteudo">
        <div id="caixa">
            <form action="" method="post">
                <div class="secao"><label for="cpf">CPF</label><input type="text" required></div>
                <div class="secao"><label for="senha">Senha</label><input type="password" required><a href="" id="esquecisenha">Esqueci minha senha</a></div>
                <input type="submit" value="ENTRAR" id="entrar">
                <a href="cadastro.php" id="cadastre">Não possui uma conta? Cadastre-se.</a>
            </form>
        </div>
    </div>
</body>
</html>