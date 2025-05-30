<?php
session_start();
include("../../php/connectDB.php");

if ($_SESSION['loggedin'] !== true) {
    header("Location: ../login/login.php");
    exit;
}

$username = $_GET['username'] ?? $_SESSION['username'];
$own = ($username == $_SESSION['username']);
// Busca os dados do usuário
$query = "SELECT 
    u.*,
    (SELECT COUNT(*) FROM registros_biologicos rb WHERE rb.id_usuario = u.id) AS total_registros,
    (SELECT COUNT(*) FROM ocorrencias o WHERE o.id_usuario = u.id) AS total_ocorrencias,
    (SELECT COUNT(*) FROM curtidas_usuarios cu WHERE cu.id_usuario = u.id) AS total_curtidas
FROM usuarios u
WHERE u.nome_usuario = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if (!$result || !$user_data = $result->fetch_assoc()) {
    die("Usuário não encontrado");
}

// Formata os dados
$usuario = [
    "id" => $user_data["id"],
    "nome" => $user_data["nome"],
    "sobrenome" => $user_data["sobrenome"],
    "nome_usuario" => $user_data["nome_usuario"],
    "imagem" => $user_data["imagem_perfil"] ?? "https://static.vecteezy.com/system/resources/previews/009/292/244/non_2x/default-avatar-icon-of-social-media-user-vector.jpg",
    "especialista" => $user_data["nivel_acesso"] == "especialista"
];

$estatisticas = [
    "registros" => $user_data["total_registros"],
    "ocorrencias" => $user_data["total_ocorrencias"],
    "curtidas" => $user_data["total_curtidas"]
];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de <?= htmlspecialchars($usuario['nome_usuario']) ?></title>
    <link rel="stylesheet" href="../default/default.css">
    <link rel="stylesheet" href="../incidents/incidents.css">
    <link rel="stylesheet" href="profile.css">
    <link rel="preload" as="image" href="<?php echo $usuario['imagem'] ?>">
</head>
<body>
    <div id="header">
        <h1>ecoframe</h1>
        <span>Foto: José Aparecido dos Santos</span>
    </div>
    <div id="content">
        <div class="back">
            <a href="../explore/explore.php"><i class="fa-solid fa-arrow-left"></i></a>
        </div>
        <div class="page">
            <div class="profile-header">
                <div class="profile-left">
                    <div class="profile-avatar">
                        <img id="pfp" src="<?= htmlspecialchars($usuario['imagem']) ?>" alt="Foto de perfil">
                    </div>
                    <div class="profile-info">
                        <h2 id="name"><?= htmlspecialchars($usuario['nome'] . ' ' . htmlspecialchars($usuario['sobrenome'])) ?></h2>
                        <?php if ($usuario['especialista']): ?>
                            <div id="specialist" class="badge">Especialista</div>
                        <?php endif; ?>
                        <p id="username">@<?= htmlspecialchars($usuario['nome_usuario']) ?></p>
                    </div>
                </div>
                <div class="profile-right">
                    <div class="stats">
                        <div><strong id="recCount"><?= $estatisticas['registros'] ?></strong><span>Registros</span></div>
                        <div><strong id="incCount"><?= $estatisticas['ocorrencias'] ?></strong><span>Ocorrências</span></div>
                        <div><strong id="likeCount"><?= $estatisticas['curtidas'] ?></strong><span>Curtidas</span></div>
                    </div>
                </div>
                <?php if ($own): ?>
                    <a id="edit-profile"><i class="fa-solid fa-gear"></i></a>
                    <a href="../../php/logout.php" id="logout"><i class="fa-solid fa-right-from-bracket"></i></a>
                <?php endif; ?>
            </div>
            <div class="profile-tabs">
                <button class="tab active" data-tab="registros">Registros</button>
                <button class="tab" data-tab="ocorrencias">Ocorrências</button>
                <button class="tab" data-tab="curtidos">Curtidos</button>
            </div>
            <div class="tab-content active" id="registros">
                <div class="posts-list"></div>
                <div id="div-see-more">
                    <button id="btn-see-more">Ver mais</button>
                </div>
            </div>
            <div class="tab-content" id="ocorrencias">
                <div class="posts-list"></div>
                <div id="div-see-more">
                    <button id="btn-see-more">Ver mais</button>
                </div>
            </div>
            <div class="tab-content" id="curtidos">
                <div class="posts-list"></div>
                <div id="div-see-more">
                    <button id="btn-see-more">Ver mais</button>
                </div>
            </div>
        </div>
    </div>
</body>
    <script src="https://kit.fontawesome.com/c68ccb89e7.js" crossorigin="anonymous"></script>
    <script>
    window.userData = { // Adicionando ao objeto window para garantir escopo global
        username: "<?= $usuario['nome_usuario'] ?>",
        own: <?= $own ? 'true' : 'false' ?>,
        isSpecialist: <?= $usuario['especialista'] ? 'true' : 'false' ?>,
        stats: {
            registros: <?= $estatisticas['registros'] ?>,
            ocorrencias: <?= $estatisticas['ocorrencias'] ?>,
            curtidas: <?= $estatisticas['curtidas'] ?>
        }
    };
    </script>
    <script src="profile.js"></script>
</html>