<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Cadastro</title>
        <link rel="stylesheet" href="../padroes/padraoPag.css">
        <link rel="stylesheet" href="../cadastro/cadastro.css">
    </head>
    <body>
        <div id="header">
            <h1>ecoframe</h1>
            <span>Foto: José Aparecido dos Santos</span>
        </div>
        <div id="navbar">
            <a href="../index/index.php">INÍCIO</a>
            <a href="../explorar/explorar.php">EXPLORAR</a>
            <a href="../login/login.php" class="selected">ENTRE / CADASTRE-SE</a>
        </div>
        <div id="conteudo">
            <div id="caixa">
            <form action="../..//php_funcoes/cadastrar.php" method="post">
                    <div class="flex">
                        <div class="caixinha"><label for="nome">Nome:</label></div>
                        <div class="caixinha"><input type="text" name="nome" required></div>
                    </div>
                    <div class="flex">
                        <div class="caixinha"><label for="sobrenome">Sobrenome:</label></div>
                        <div class="caixinha"><input type="text" name="sobrenome" required></div>
                    </div>
                    <div class="flex">
                        <div class="caixinha"><label for="cpf">CPF:</label></div>
                        <div class="caixinha"><input type="text" name="cpf" required></div>
                    </div>
                    <div class="flex">
                        <div class="caixinha"><label for="email">Email:</label></div>
                        <div class="caixinha"><input type="email" name="email" required></div>
                    </div>
                    <div class="flex">
                        <div class="caixinha"><label for="senha">Senha:</label></div>
                        <div class="caixinha"><input type="password" name="senha" required></div>
                    </div>
                    <div class="flex">
                        <div class="caixinha"><label for="senha2">Repita a senha:</label></div>
                        <div class="caixinha"><input type="password" name="senha2" required></div>
                    </div>
                    <div class="flex">
                        <div class="caixinha">
                            <label for="curriculo">Sou um especialista:</label>
                        </div>
                        <div class="caixinha">      
                            <input type="radio" name="categoria" id="sim" value="sim">
                            <label for="sim" id="simlabel">Sim</label>
                            
                            <input type="radio" name="categoria" id="nao" value="nao" checked>
                            <label for="nao">Não</label>
                        </div>                  
                    </div>
                    <div id="campoLattes" style="display: none; margin-top: 10px;">
                        <label for="lattes">Link do currículo Lattes:</label>
                        <input type="url" name="lattes" id="lattes" placeholder="https://lattes.cnpq.br/..." style="width: 100%;">
                    </div>
                    <input type="submit" value="CADASTRAR" id="cadastrar">
                    <a href="../login/login.php" id="facalogin">Já possui uma conta? Faça login.</a>
                </form>
            </div>
        </div>
    </body>
</html>
<script src="cadastro.js"></script>