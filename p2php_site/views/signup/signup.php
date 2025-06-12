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
            <h1 onclick="window.location.href='../explore/explore.php'">ecoframe</h1>
            <span>Foto: José Aparecido dos Santos</span>
        </div>
        <div id="navbar">
             <a href="../index/index.php">INÍCIO</a>
            <a href="../explore/explore.php">EXPLORAR</a>
                <?php 
            if(isset($_SESSION['loggedin'])) {
                echo '<a href="../profile/profile.php?username=' . $_SESSION['username'] . '" id="profile-link">PERFIL</a>';
            } else {
                echo '<a href="../login/login.php" id="login-link"  class="selected">ENTRE</a>';
            }
        ?>
        </div>
        <div id="content">
            <div id="section">
                <div class="image-pick">
                    <div id="div-image-selection" onclick="upload()">
                        <div id="image-overlay">
                            <span id="image-overlay-text">Clique para selecionar uma imagem</span>
                        </div>
                        <div id="image-selected">
                            <div id="image-preview" class="hidden"></div>
                        </div>

                    </div>
                </div>
                <form action="../../php/signup.php" method="post" enctype="multipart/form-data">
                    <input type="file" name="image" accept="image/*" id="image" hidden
                        onchange="previewImage(event)">
                    <div class="flex">
                        <div class="box"><label for="name">Nome:</label></div>
                        <div class="box"><input type="text" name="name" required></div>
                    </div>
                    <div class="flex">
                        <div class="box"><label for="lastname">Sobrenome:</label></div>
                        <div class="box"><input type="text" name="lastname" required></div>
                    </div>
                    <div class="flex un">
                        <div class="box"><label for="username">Nome de Usuário:</label></div>
                        <div class="box" style="position: relative;">
                            <input type="text" name="username" id="username" required>
                            <span class="username-status" id="username-status"></span>
                        </div>
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
                            <input type="radio" name="category" id="yes" value="sim">
                            <label for="yes" id="yeslabel">Sim</label>
                            
                            <input type="radio" name="category" id="no" value="nao" checked>
                            <label for="no">Não</label>
                        </div>                  
                    </div>
                    <div id="lattesfield" style="display: none; margin-top: 10px;">
                        <label for="position">Cargo:</label>
                        <input type="text" name="position" id="position" style="width: 100%;">
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