<?php
    session_start();
    include_once(__DIR__ . '/../../config/headers.php');
    include_once(__DIR__ . '/../../config/conexao.php');

    if (!isset($_GET['id'])) {
        echo json_encode([
            'status' => 'nok',
            'mensagem' => 'ID não fornecido', 
            'data' => []
        ]);
        exit;
    }

    $id_usuario = $_SESSION['usuario']['id'];

    $stmt = $conexao->prepare("SELECT * FROM ticket_suporte WHERE id = ? AND id_usuario = ?");
    $stmt->bind_param('ii', $_GET['id'], $id_usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $retorno = [
            'status' => 'ok',
            'mensagem' => 'Sucesso',
            'data' => $resultado->fetch_assoc()
        ];
    } else {
        $retorno = [
            'status' => 'nok',
            'mensagem' => 'Ticket não encontrado',
            'data' => []
        ];
    }

    $stmt->close();
    $conexao->close();
    header("Content-type: application/json; charset=utf-8");
    echo json_encode($retorno);
