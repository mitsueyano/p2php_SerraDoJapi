<?php
header("Content-Type: application/json");
include 'conectaBD.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $dados = json_decode(file_get_contents("php://input"), true);

    if (!$dados || !isset($dados['post_id']) || !isset($dados['usuario_id'])) {
        echo json_encode(["success" => false, "mensagem" => "Dados inválidos."]);
        exit;
    }

    $post_id = intval($dados['post_id']);
    $usuario_id = intval($dados['usuario_id']);

    // Verifica se o usuário já curtiu
    $verifica = "SELECT * FROM curtidas_usuarios WHERE id_usuario = $usuario_id AND id_registro = $post_id";
    $result = mysqli_query($conexao, $verifica);

    if (mysqli_num_rows($result) > 0) {
        // Já curtiu — remover curtida
        $query1 = "UPDATE registros_biologicos SET qtde_likes = qtde_likes - 1 WHERE id = $post_id";
        $query2 = "DELETE FROM curtidas_usuarios WHERE id_usuario = $usuario_id AND id_registro = $post_id";
        $liked = false;
    } else {
        // Ainda não curtiu — adicionar curtida
        $query1 = "UPDATE registros_biologicos SET qtde_likes = qtde_likes + 1 WHERE id = $post_id";
        $query2 = "INSERT INTO curtidas_usuarios (id_usuario, id_registro) VALUES ($usuario_id, $post_id)";
        $liked = true;
    }

    $res1 = mysqli_query($conexao, $query1);
    $erro1 = !$res1 ? mysqli_error($conexao) : null;

    $res2 = mysqli_query($conexao, $query2);
    $erro2 = !$res2 ? mysqli_error($conexao) : null;

    if ($res1 && $res2) {
        echo json_encode(["success" => true, "liked" => $liked]);
    } else {
        echo json_encode([
            "success" => false,
            "erro1" => $erro1,
            "erro2" => $erro2
        ]);
    }
}

$conexao->close();
?>
