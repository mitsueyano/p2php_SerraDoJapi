<?php //---Função para cadastrar novo usuário no banco de dados---//

    include '../php/connectDB.php';

$nome = $_POST["name"];
$sobrenome = $_POST["lastname"];
$cpf = $_POST["cpf"];
$email = $_POST["email"];
$nomeUsuario = $_POST["username"];
$password = password_hash($_POST["password"], PASSWORD_DEFAULT);
$category = strtolower($_POST["category"]); // segurança extra
$nivel = ($category === "sim") ? "especialista" : "comum";
$lattes = ($nivel === "especialista" && !empty($_POST["lattes"])) ? $_POST["lattes"] : null;
$position = ($nivel === "especialista" && !empty($_POST["position"])) ? $_POST["position"] : null;

$stmt = $conn->prepare("INSERT INTO usuarios (cpf, nome, sobrenome, email, nome_usuario senha, nivel_acesso, link_lattes, cargo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssss", $cpf, $nome, $sobrenome, $email, $nomeUsuario, $password, $nivel, $lattes, $position);

if (!$stmt->execute()) {
    die("Erro ao cadastrar usuário: " . $stmt->error);
} else {
    session_start();
    $_SESSION["logado"] = true;
    $_SESSION["user"] = $nome;
    $_SESSION["nivel"] = $nivel;
    $_SESSION["id_usuario"] = $stmt->insert_id;

    header("Location: ../views/profile/profile.php");
    exit();
}

?>
