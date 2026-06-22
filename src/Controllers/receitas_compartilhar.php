<?php
    include_once(__DIR__ . '/../../config/headers.php');
    include_once(__DIR__ . '/../../config/conexao.php');
    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }

    $id_usuario = $_SESSION['usuario']['id'];
    $id_receita = $_GET['id'] ?? null;

    if(!$id_receita){
        echo json_encode(['status' => 'nok', 'mensagem' => 'ID não informado']);
        exit;
    }

    $stmt = $conexao->prepare("SELECT link_compart FROM receita WHERE id = ? AND id_usuario = ?");
    $stmt->bind_param('ii', $id_receita, $id_usuario);
    $stmt->execute();
    $resultado = $stmt->get_result()->fetch_assoc();

    if(!$resultado){
        echo json_encode(['status' => 'nok', 'mensagem' => 'Receita não encontrada']);
        exit;
    }

    if(!empty($resultado['link_compart'])){
        $token = $resultado['link_compart'];
    } else {
        $token = bin2hex(random_bytes(32));
        $stmt  = $conexao->prepare("UPDATE receita SET link_compart = ? WHERE id = ? AND id_usuario = ?");
        $stmt->bind_param('sii', $token, $id_receita, $id_usuario);
        $stmt->execute();
    }

    $config = parse_ini_file(__DIR__ . '/../../.env'); // ajusta o caminho se necessário
    $base   = rtrim($config['NGROK_URL'], '/');
    $link   = $base . '/mykeeper/receitas_guest?token=' . $token;

    echo json_encode([
        'status'   => 'ok',
        'mensagem' => 'Link gerado com sucesso',
        'link'     => $link
    ]);

    $stmt->close();
    $conexao->close();
?>