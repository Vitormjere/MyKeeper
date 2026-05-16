<?php
    include_once(__DIR__ . '/../../config/headers.php');
    include_once(__DIR__ . '/../../config/conexao.php');

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $retorno = [
        'status' => '', 
        'mensagem' => ''
    ];

    $id_usuario       = $_SESSION['usuario']['id'];

    $id               = $_POST['id'] ?? 0;
    $novoStatus       = $_POST['status'] ?? '';
    $statusPermitidos = ['concluida', 'arquivada'];
    
    if (!$id || !in_array($novoStatus, $statusPermitidos)) {
        $retorno = ['status' => 'erro', 'mensagem' => 'Dados inválidos.'];
        echo json_encode($retorno);
        exit;
    }

    $stmt = $conexao->prepare(
        "UPDATE lista_compras SET status_compra = ? WHERE id = ? AND id_usuario = ?"
    );

    $stmt->bind_param("sii", $novoStatus, $id, $id_usuario);

    if ($stmt->execute() && $stmt->affected_rows > 0) {

        $retorno = [
            'status' => 'ok', 
            'mensagem' => 'Status atualizado com sucesso.'
        ];

    } else {
        $retorno = [
            'status' => 'erro', 
            'mensagem' => 'Erro ao atualizar ou lista não encontrada.'
        ];
    }

    $stmt->close();
    $conexao->close();

    header("Content-type: application/json; charset=utf-8");
    echo json_encode($retorno);
?>