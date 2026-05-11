<?php
    session_start();
    include_once(__DIR__ . '/../../config/headers.php');
    include_once(__DIR__ . '/../../config/conexao.php');

    if (!isset($_GET['id'])) {
        echo json_encode([
            'status'   => 'nok',
            'mensagem' => 'ID do usuário não fornecido',
            'data'     => []
        ]);
        exit;
    }

    $stmt = $conexao->prepare("SELECT * FROM ticket_suporte WHERE id_usuario = ?");
    $stmt->bind_param('i', $_GET['id']);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $tabela = []; 
    while ($linha = $resultado->fetch_assoc()) {
        $tabela[] = $linha;
    }

    if (count($tabela) > 0) { 
        $retorno = ['status' => 'ok', 'mensagem' => 'Sucesso', 'data' => $tabela];
    } else {
        $retorno = ['status' => 'nok', 'mensagem' => 'Não há registros', 'data' => []];
    }

    $stmt->close();
    $conexao->close();
    header("Content-type: application/json; charset=utf-8");
    echo json_encode($retorno);
