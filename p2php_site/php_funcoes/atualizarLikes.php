<?php
    header("Content-Type: application/json");
    include 'conectaBD.php';

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $dados = json_decode(file_get_contents("php://input"), true);

        if (!$dados || !isset($dados['post_id']) || !isset($dados['usuario_id'])) {
            echo json_encode(["success" => false, "mensagem" => "Dados inválidos."]);
            exit;
        }

        $post_id = $dados['post_id'];
        $usuario_id = $dados['usuario_id'];

        $query1 = "UPDATE registros_biologicos SET qtdelikes = qtdelikes + 1 WHERE id = '$post_id'";
        $query2 = "INSERT INTO curtidas_usuarios (usuario_id, post_id) VALUES ('$usuario_id', '$post_id')";

        $res1 = mysqli_query($conexao, $query1);
        $res2 = mysqli_query($conexao, $query2);

        if ($res1 && $res2) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode([
                "success" => false,
                "erro" => mysqli_error($conexao)
            ]);
        }
    }

    $conexao->close();
?>
