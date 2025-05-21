<?php
header("Content-Type: application/json");
include 'connectDB.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data || !isset($data['postid']) || !isset($data['userid'])) {
        echo json_encode(["success" => false, "mensagem" => "Dados inválidos."]);
        exit;
    }

    $postid = intval($data['postid']);
    $userid = intval($data['userid']);

    // Verifica se o usuário já curtiu
    $verify = "SELECT * FROM curtidas_usuarios WHERE id_usuario = $userid AND id_registro = $postid";
    $result = mysqli_query($conn, $verify);

    if (mysqli_num_rows($result) > 0) {
        // Já curtiu — remover curtida
        $query1 = "UPDATE registros_biologicos SET qtde_likes = qtde_likes - 1 WHERE id = $postid";
        $query2 = "DELETE FROM curtidas_usuarios WHERE id_usuario = $userid AND id_registro = $postid";
        $liked = false;
    } else {
        // Ainda não curtiu — adicionar curtida
        $query1 = "UPDATE registros_biologicos SET qtde_likes = qtde_likes + 1 WHERE id = $postid";
        $query2 = "INSERT INTO curtidas_usuarios (id_usuario, id_registro) VALUES ($userid, $postid)";
        $liked = true;
    }

    $res1 = mysqli_query($conn, $query1);
    $error1 = !$res1 ? mysqli_error($conn) : null;

    $res2 = mysqli_query($conn, $query2);
    $error2 = !$res2 ? mysqli_error($conn) : null;

    if ($res1 && $res2) {
        echo json_encode(["success" => true, "liked" => $liked]);
    } else {
        echo json_encode([
            "success" => false,
            "erro1" => $error1,
            "error2" => $error2
        ]);
    }
}

$conn->close();
?>
