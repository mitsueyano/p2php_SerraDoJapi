<?php //--- Função para validação de login ---//

    session_start();
    include '../php_funcoes/conectaBD.php';

    $cpf = $_POST["cpf"];
    $senha = $_POST["senha"];
    $query = "SELECT * FROM usuarios WHERE cpf = ?";
    $stmt = mysqli_prepare($conexao, $query); //evita SQL injection

    //Verifica se a preparação foi bem-sucedida
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $cpf);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            if (password_verify($senha, $row["senha"])) { //Verifica o hash de senha
                $_SESSION["logado"] = true;
                $_SESSION["user"] = $row["nome"];
                $_SESSION["nivel"] = $row["nivel_acesso"];
                $_SESSION["id_usuario"] = $row["id"];

                header("Location: ../telas/explorar/explorar.php");
                exit();
            }
        }   
    } else {
        header("Location: ../telas/login/login.php?erro=LoginIncorreto");
        exit();
    }
    
?>
