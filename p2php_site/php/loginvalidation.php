<?php
session_start();
include '../php/connectDB.php';

$cpf = $_POST["cpf"];
$password = $_POST["password"];
$query = "SELECT * FROM usuarios WHERE cpf = ?";
$stmt = mysqli_prepare($conn, $query);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $cpf);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        var_dump($row); // ✅ Apenas uma vez

        if (password_verify($password, $row["senha"])) {
            $_SESSION["loggedin"] = true;
            $_SESSION["user"] = $row["nome"];
            $_SESSION["userid"] = $row["id"];
            $_SESSION["access"] = $row['nivel_acesso'];
            $_SESSION["username"] = $row["nome_usuario"];

            header("Location: ../views/explore/explore.php");
            exit();
        }
    }

    header("Location: ../views/login/login.php?error=LoginIncorreto");
    exit();
} else {

    header("Location: ../views/login/login.php?error=ErroQuery");
    exit();
}
    
?>
