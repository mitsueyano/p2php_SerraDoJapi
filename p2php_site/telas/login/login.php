<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
        <link rel="stylesheet" href="../padroes/padraoPag.css">
        <link rel="stylesheet" href="../login/login.css">
    </head>
    <body>
        <div id="header">
            <h1>ecoframe</h1>
            <span>Foto: José Aparecido dos Santos</span>
        </div>
        <div id="navbar">
            <a href="../index/index.php">INÍCIO</a>
            <a href="../explorar/explorar.php">EXPLORAR</a>
            <a href="../login/login.php" id="login-link" class="selected">ENTRE / CADASTRE-SE</a>
            <a href="../perfil/perfil.php" id="perfil-link" style="display: none;" class="selected">PERFIL</a>
        </div>
        <div id="conteudo">
            <div id="caixa">
                <form action="../../php_funcoes/conectaLogin.php" method="post">
                    <div class="secao"><label for="cpf">CPF</label><input type="text" required id="cpf" name="cpf"></div>
                    <div class="secao"><label for="senha">Senha</label><input type="password" required id="senha" name="senha"><a href="" id="esquecisenha">Esqueci minha senha</a></div>
                    <span id="msg-erro"></span>
                    <input type="submit" value="ENTRAR" id="entrar">
                    <a href="../cadastro/cadastro.php" id="cadastre">Não possui uma conta? Cadastre-se.</a>
                </form>
            </div>
        </div>
    </body>
</html>
<script src="../login/login.js"></script>
<script>
    sessionStorage.setItem("logado", "<?php echo isset($_SESSION['logado']) && $_SESSION['logado'] === true ? 'true' : 'false'; ?>");
</script>