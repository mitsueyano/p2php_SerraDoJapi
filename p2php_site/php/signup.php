<?php //---Função para cadastrar novo usuário no banco de dados---//

    include '../php/connectDB.php';

    $nome = $_POST["name"];
    $sobrenome = $_POST["lastname"];
    $cpf = $_POST["cpf"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $category = $_POST["category"];
    $nivel = ($category === "sim") ? "especialista" : "comum";
    $lattes = ($nivel === "especialista" && !empty($_POST["lattes"])) ? $_POST["lattes"] : null;

    $query = "INSERT INTO usuarios (cpf, nome, sobrenome, email, senha, nivel_acesso, link_lattes)
              VALUES ('$cpf', '$nome', '$sobrenome', '$email', '$password', '$nivel', " . ($lattes ? "'$lattes'" : "NULL") . ")";

    $result = mysqli_query($conn, $query);
    if (!$result) {
        die("Erro ao cadastrar usuário: " . mysqli_error($conn));
    } else {
            session_start();
            $_SESSION["logado"] = true;
            $_SESSION["user"] = $nome;
            $_SESSION["nivel"] = $nivel;
            $_SESSION["id_usuario"] = mysqli_insert_id($conn);

            header("Location: ../views/profile/profile.php");
            exit();
    }
?>
