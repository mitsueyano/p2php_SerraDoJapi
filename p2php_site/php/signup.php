<?php
include '../php/connectDB.php';
require '../vendor/autoload.php';
use Dotenv\Dotenv;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

//URL padrão para perfil, caso o usuário não envie imagem
const DEFAULT_PROFILE_IMAGE = 'https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fi.pinimg.com%2F736x%2F42%2Fba%2F10%2F42ba10b0548031b218ac059c71b079c1.jpg&f=1&nofb=1&ipt=f7ca252fbbb9d84e98ed24702461f362fb5f5e18006e8248b7c3bbe7d3bd0947';
try {
    $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();

    Configuration::instance([
        'cloud' => [
            'cloud_name' => $_ENV['CLOUDINARY_CLOUD_NAME'],
            'api_key' => $_ENV['CLOUDINARY_API_KEY'],
            'api_secret' => $_ENV['CLOUDINARY_API_SECRET'],
        ],
        'url' => ['secure' => true]
    ]);

    //Coleta os dados
    $requiredFields = ['name', 'lastname', 'cpf', 'email', 'username', 'password', 'category'];
    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])) {
            throw new Exception("O campo " . ucfirst($field) . " é obrigatório.");
        }
    }

    $nome = trim($_POST["name"]);
    $sobrenome = trim($_POST["lastname"]);
    $cpf = preg_replace('/[^0-9]/', '', $_POST["cpf"]);
    $email = filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL);
    $nomeUsuario = trim($_POST["username"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $category = strtolower(trim($_POST["category"]));
    $nivel = ($category === "sim") ? "especialista" : "comum";
    $lattes = ($nivel === "especialista" && !empty($_POST["lattes"])) ? trim($_POST["lattes"]) : null;
    $position = ($nivel === "especialista" && !empty($_POST["position"])) ? trim($_POST["position"]) : null;

    $imagemPerfil = DEFAULT_PROFILE_IMAGE; //Define a imagem padrão inicialmente
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {// Se o usuário enviou uma imagem de perfil válida
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        $fileType = mime_content_type($_FILES['image']['tmp_name']);
        
        if (!in_array($fileType, $allowedTypes)) {
            throw new Exception("Tipo de arquivo inválido. Use apenas JPEG, PNG ou WebP.");
        }

        if ($_FILES['image']['size'] > 5 * 1024 * 1024) { //Valida o tamanho (máx. 5MB)
            throw new Exception("A imagem deve ter no máximo 5MB.");
        }

        //Envia para o Cloudinary e aplica transformações
        $tmpFile = $_FILES['image']['tmp_name'];
        $result = (new UploadApi())->upload($tmpFile, [
            'folder' => $_ENV['CLOUDINARY_FOLDER_PROFILES'],
            'transformation' => [
                ['width' => 200, 'height' => 200, 'crop' => 'fill'],
                ['quality' => 'auto']
            ]
        ]);
        $imagemPerfil = $result['secure_url'];
    }

    //Query para cadastrar o usuário
    $stmt = $conn->prepare("INSERT INTO usuarios (cpf, nome, sobrenome, email, nome_usuario, senha, nivel_acesso, link_lattes, cargo, imagem_perfil) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssss", $cpf, $nome, $sobrenome, $email, $nomeUsuario, $password, $nivel, $lattes, $position, $imagemPerfil);

    if (!$stmt->execute()) {
        throw new Exception("Erro ao cadastrar usuário: " . $stmt->error);
    }
    
    //Cria a sessão do usuário após cadastro
    session_start();
    $_SESSION["loggedin"] = true;
    $_SESSION["user"] = $nome;
    $_SESSION["access"] = $nivel;
    $_SESSION["userid"] = $stmt->insert_id;
    $_SESSION["username"] = $nomeUsuario;
    $_SESSION["pfp"] = $imagemPerfil;

    $stmt->close();
    $conn->close();

    header("Location: ../views/explore/explore.php");
    exit();

} catch (Exception $e) {
    if (isset($stmt)) $stmt->close();
    if (isset($conn)) $conn->close();
    
    die("Erro: " . $e->getMessage());
}