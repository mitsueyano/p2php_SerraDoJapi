<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Cadastro</title>
        <link rel="stylesheet" href="../default/default.css">
        <link rel="stylesheet" href="../signup/signup.css">
    </head>
    <body>
        <div id="header">
            <h1>ecoframe</h1>
            <span>Foto: José Aparecido dos Santos</span>
        </div>
        <div id="navbar">
            <a href="../index/index.php">INÍCIO</a>
            <a href="../explore/explore.php">EXPLORAR</a>
            <a href="../login/login.php" class="selected">ENTRE / CADASTRE-SE</a>
        </div>
        <div id="content">
            <div id="section">
            <form action="../../php/signup.php" method="post">
                    <div class="flex">
                        <div class="box"><label for="name">Nome:</label></div>
                        <div class="box"><input type="text" name="name" required></div>
                    </div>
                    <div class="flex">
                        <div class="box"><label for="lastname">Sobrenome:</label></div>
                        <div class="box"><input type="text" name="lastname" required></div>
                    </div>
                    <div class="flex">
                        <div class="box"><label for="cpf">CPF:</label></div>
                        <div class="box"><input type="text" name="cpf" required></div>
                    </div>
                    <div class="flex">
                        <div class="box"><label for="email">Email:</label></div>
                        <div class="box"><input type="email" name="email" required></div>
                    </div>
                    <div class="flex">
                        <div class="box"><label for="password">Senha:</label></div>
                        <div class="box"><input type="password" name="password" required></div>
                    </div>
                    <div class="flex">
                        <div class="box"><label for="password2">Repita a senha:</label></div>
                        <div class="box"><input type="password" name="password2" required></div>
                    </div>
                    <div class="flex">
                        <div class="box">
                            <label for="cv">Sou um especialista:</label>
                        </div>
                        <div class="box">      
                            <input type="radio" name="category" id="yes" value="yes">
                            <label for="yes" id="yeslabel">Sim</label>
                            
                            <input type="radio" name="category" id="no" value="no" checked>
                            <label for="no">Não</label>
                        </div>                  
                    </div>
                    <div id="Lattesfield" style="display: none; margin-top: 10px;">
                        <label for="lattes">Link do currículo Lattes:</label>
                        <input type="url" name="lattes" id="lattes" placeholder="https://lattes.cnpq.br/..." style="width: 100%;">
                    </div>
                    <input type="submit" value="CADASTRAR" id="signup">
                    <a href="../login/login.php" id="login">Já possui uma conta? Faça login.</a>
                </form>
            </div>
        </div>
    </body>
</html>
<script src="signup.js"></script>