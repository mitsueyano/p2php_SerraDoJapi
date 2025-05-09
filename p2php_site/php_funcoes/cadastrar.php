<?php //---Função para cadastrar novo usuário no banco de dados---//

    include '../php_funcoes/conectaBD.php';

    $nome = $_POST["nome"];
    $sobrenome = $_POST["sobrenome"];
    $cpf = $_POST["cpf"];
    $email = $_POST["email"];
    $senha = password_hash($_POST["senha"], PASSWORD_DEFAULT);
    $categoria = $_POST["categoria"];
    $nivel = ($categoria === "sim") ? "especialista" : "comum";
    $lattes = ($nivel === "especialista" && !empty($_POST["lattes"])) ? $_POST["lattes"] : null; //Se o campo lattes estiver vazil, define como null

    $query = "INSERT INTO usuarios (cpf, nome, sobrenome, email, senha, nivel_acesso, link_lattes)
              VALUES ('$cpf', '$nome', '$sobrenome', '$email', '$senha', '$nivel', " . ($lattes ? "'$lattes'" : "NULL") . ")";

    $resultado = mysqli_query($conexao, $query);
    if (!$resultado) {
        die("Erro ao cadastrar usuário: " . mysqli_error($conexao));
    } else {
            session_start();
            $_SESSION["logado"] = true;
            $_SESSION["user"] = $nome;
            $_SESSION["nivel"] = $nivel;
            $_SESSION["id_usuario"] = mysqli_insert_id($conexao); //ID do novo usuário

            header("Location: ../telas/perfil/perfil.php"); //Redireciona direto para a nova página de perfil do usuário
            exit();
    }
?>
