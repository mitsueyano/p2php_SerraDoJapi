<?php
session_start();
header("Content-Type: application/json");
require_once("connectDB.php");

if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID do registro não fornecido']);
    exit;
}

//Coleta os dados
$registroId = intval($_GET['id']);
if ($registroId <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID inválido']);
    exit;
}
$userId = isset($_SESSION['userid']) ? intval($_SESSION['userid']) : 0;

//Query
try {
    $sql = "
        SELECT 
            rb.*,
            u.nome,
            u.sobrenome,
            u.nome_usuario,
            u.imagem_perfil,
            u.nivel_acesso,
            ct.nome_popular,
            ct.classe,
            ct.ordem,
            ct.familia,
            ct.especie,
            c.nome AS categoria,
            g.latitude,
            g.longitude,
            g.nome_lugar,
            DATE_FORMAT(rb.data_observacao, '%d/%m/%Y') AS data_observacao_formatada,
            DATE_FORMAT(rb.data_publicacao, '%d/%m/%Y') AS data_publicacao_formatada,
            (
                SELECT COUNT(*) 
                FROM curtidas_usuarios cu
                WHERE cu.id_usuario = ? 
                AND cu.id_registro = rb.id
            ) AS liked,
            (
                SELECT COUNT(*)
                FROM comentarios co
                WHERE co.id_registro = rb.id
                AND co.id_comentario_pai IS NULL
            ) AS qtde_coment,
            (
                SELECT COUNT(*)
                FROM comentarios co
                WHERE co.id_registro = rb.id
            ) AS total_comentarios
        FROM registros_biologicos rb
        JOIN usuarios u ON rb.id_usuario = u.id
        LEFT JOIN classificacao_taxonomica ct ON rb.id_taxonomia = ct.id
        LEFT JOIN categoria c ON ct.id_categoria = c.id
        JOIN geolocalizacao g ON rb.id_geolocalizacao = g.id
        WHERE rb.id = ?
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $userId, $registroId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Registro não encontrado']);
        exit;
    }
    $registro = $result->fetch_assoc();

    //Obtém os comentários principais e suas respostas
    $sqlComentarios = "
        SELECT 
            c.id,
            c.id_usuario,
            c.id_comentario_pai,
            c.conteudo,
            c.data_publicacao,
            u.nome,
            u.sobrenome,
            u.nome_usuario,
            u.imagem_perfil,
            u.nivel_acesso,
            CASE 
                WHEN c.id_comentario_pai IS NULL THEN 'comentario'
                ELSE 'resposta'
            END AS tipo
        FROM comentarios c
        JOIN usuarios u ON c.id_usuario = u.id
        WHERE c.id_registro = ?
        ORDER BY 
            c.data_publicacao DESC,  -- Ordena por data decrescente (mais recente primeiro)
            COALESCE(c.id_comentario_pai, c.id) ASC  -- Mantém a ordem das respostas
        LIMIT 50
    ";
    
    $stmtComentarios = $conn->prepare($sqlComentarios);
    $stmtComentarios->bind_param("i", $registroId);
    $stmtComentarios->execute();
    $resultComentarios = $stmtComentarios->get_result();

    //Estrutura os dados hierarquicamente
    $comentarios = [];
    $respostasPorPai = [];
    
    while ($comentario = $resultComentarios->fetch_assoc()) {
        $item = [
            'id' => $comentario['id'],
            'usuario' => [
                'id' => $comentario['id_usuario'],
                'nome' => $comentario['nome'],
                'sobrenome' => $comentario['sobrenome'],
                'username' => $comentario['nome_usuario'],
                'imagem_perfil' => $comentario['imagem_perfil'],
                'nivel_acesso' => $comentario['nivel_acesso'],
            ],
            'conteudo' => $comentario['conteudo'],
            'data_publicacao' => $comentario['data_publicacao'],
            'respostas' => [],
            'id_registro' => $registroId
        ];
        
        if ($comentario['tipo'] === 'comentario') {
            $comentarios[$comentario['id']] = $item;
        } else {
            if (!isset($respostasPorPai[$comentario['id_comentario_pai']])) {
                $respostasPorPai[$comentario['id_comentario_pai']] = [];
            }
            $respostasPorPai[$comentario['id_comentario_pai']][] = $item;
        }
    }
    
    //Associa as respostas aos comentários pais
    foreach ($respostasPorPai as $paiId => $respostas) {
        if (isset($comentarios[$paiId])) {
            $comentarios[$paiId]['respostas'] = $respostas;
        }
    }

    $comentariosFormatados = array_values($comentarios);

    $registro['identificacao'] = (bool)$registro['identificacao'];
    $registro['liked'] = (bool)$registro['liked'];

    //Formata os dados para retorno
    $response = [
        'success' => true,
        'data' => [
            'id' => $registro['id'],
            'usuario' => [
                'id' => $registro['id_usuario'],
                'nome' => $registro['nome'],
                'sobrenome' => $registro['sobrenome'],
                'username' => $registro['nome_usuario'],
                'imagem_perfil' => $registro['imagem_perfil'],
                'nivel_acesso' => $registro['nivel_acesso']
            ],
            'taxonomia' => [
                'nome_popular' => $registro['nome_popular'] ?: 'Não identificado',
                'classe' => $registro['classe'] ?: '',
                'ordem' => $registro['ordem'] ?: '',
                'familia' => $registro['familia'] ?: '',
                'especie' => $registro['especie'] ?: 'Não identificado',
                'categoria' => $registro['categoria'] ?: 'Fauna'
            ],
            'geolocalizacao' => [
                'latitude' => $registro['latitude'],
                'longitude' => $registro['longitude'],
                'nome_lugar' => $registro['nome_lugar']
            ],
            'data_observacao' => $registro['data_observacao_formatada'],
            'hora_observacao' => $registro['hora_observacao'],
            'descricao' => $registro['descricao'],
            'imagem_url' => $registro['url_imagem'],
            'likes' => $registro['qtde_likes'],
            'comentarios_count' => $registro['qtde_coment'],
            'total_comentarios' => $registro['total_comentarios'],
            'data_publicacao' => $registro['data_publicacao_formatada'],
            'hora_publicacao' => $registro['hora_publicacao'],
            'identificacao' => $registro['identificacao'],
            'liked' => $registro['liked'],
            'comentarios' => $comentariosFormatados
        ]
    ];

    echo json_encode($response);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro no servidor',
        'error' => $e->getMessage()
    ]);
}

$conn->close();
?>