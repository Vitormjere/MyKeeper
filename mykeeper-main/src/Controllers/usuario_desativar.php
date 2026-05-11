<?php
    session_start();
    include_once(__DIR__ . '/../../config/headers.php');
    include_once(__DIR__ . '/../../config/conexao.php');

    if (empty($_SESSION['usuario']['id'])) {
        echo json_encode([
            'status' => 'nok',
            'mensagem' => 'Usuário não autenticado'
        ]);
        exit;
    }

    $id = $_SESSION['usuario']['id'];

    $stmt = $conexao->prepare("UPDATE usuario SET conta_ativa = false WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        session_destroy();
        $retorno = [
            'status' => 'ok',
            'mensagem' => 'Conta desativada com sucesso'
        ];
    } else {
        $retorno = [
            'status' => 'nok',
            'mensagem' => 'Falha ao desativar conta'
        ];
    }

    $stmt->close();
    $conexao->close();
    header('Content-type: application/json; charset=utf-8');
    echo json_encode($retorno);
