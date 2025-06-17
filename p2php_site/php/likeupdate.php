<?php
session_start();
header("Content-Type: application/json");
include 'connectDB.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_SESSION["userid"])) {
        http_response_code(401);
        exit;
    }
    $data = json_decode(file_get_contents("php://input"), true);
    if (!$data || !isset($data['postid'])) {
        echo json_encode(["success" => false, "mensagem" => "Dados inválidos."]);
        exit;
    }

    //Coleta os dados
    $postid = intval($data['postid']);
    $userid = $_SESSION["userid"];

    $stmt = $conn->prepare("SELECT 1 FROM curtidas_usuarios WHERE id_usuario = ? AND id_registro = ?");
    $stmt->bind_param("ii", $userid, $postid);
    $stmt->execute();
    $stmt->store_result();
    $liked = false;
    if ($stmt->num_rows > 0) { //Se o usuário já curtiu

        //Prepara atualização para diminuir o contador de likes no post
        $query1 = $conn->prepare("UPDATE registros_biologicos SET qtde_likes = qtde_likes - 1 WHERE id = ?");
        $query1->bind_param("i", $postid);
        //Prepara exclusão da curtida do usuário naquele post
        $query2 = $conn->prepare("DELETE FROM curtidas_usuarios WHERE id_usuario = ? AND id_registro = ?");
        $query2->bind_param("ii", $userid, $postid);

    } else { //Se o usuário ainda não curtiu

        //Prepara atualização para aumentar o contador de likes no post
        $query1 = $conn->prepare("UPDATE registros_biologicos SET qtde_likes = qtde_likes + 1 WHERE id = ?");
        $query1->bind_param("i", $postid);
        //Prepara inserção do registro de curtida do usuário naquele post
        $query2 = $conn->prepare("INSERT INTO curtidas_usuarios (id_usuario, id_registro) VALUES (?, ?)");
        $query2->bind_param("ii", $userid, $postid);
        $liked = true;

    }

    $res1 = $query1->execute();
    $res2 = $query2->execute();

    if ($res1 && $res2) {
        echo json_encode(["success" => true, "liked" => $liked]);
    } else {
        echo json_encode([
            "success" => false,
            "erro1" => $query1->error,
            "erro2" => $query2->error
        ]);
    }
    $stmt->close();
    $query1->close();
    $query2->close();
} else {
    echo "Método inválido.";
}
$conn->close();
?>