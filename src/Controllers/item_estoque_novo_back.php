<?php
    include_once(__DIR__ . '/../../config/headers.php');
    include_once(__DIR__ . '/../../config/conexao.php');
    session_start();

    $retorno = [
        'status'   => '',
        'mensagem' => '',
        'data'     => []
    ];

    $id_usuario   = $_SESSION['usuario']['id'];
    $id_estoque   = $_POST['id_estoque'];
    $id_produto   = $_POST['id_produto'];
    $quantidade   = $_POST['quantidade']   ?: null;
    $data_validade = $_POST['data_validade'] ?: null;
    $marca        = $_POST['marca']        ?: null;

    // Garante que o estoque pertence ao usuário
    $check = $conexao->prepare("SELECT id FROM estoque WHERE id = ? AND id_usuario = ?");
    $check->bind_param('ii', $id_estoque, $id_usuario);
    $check->execute();
    $check->store_result();

    if ($check->num_rows === 0) {
        echo json_encode([
            'status'   => 'nok',
            'mensagem' => 'Estoque não encontrado',
            'data'     => []
        ]);
        exit;
    }
    $check->close();

    $stmt = $conexao->prepare("
        INSERT INTO item_estoque (id_estoque, id_produto, quantidade, data_validade, marca)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->bind_param('iidss', $id_estoque, $id_produto, $quantidade, $data_validade, $marca);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $retorno = [
            'status'   => 'ok',
            'mensagem' => 'Item adicionado com sucesso',
            'data'     => []
        ];
    } else {
        $retorno = [
            'status'   => 'nok',
            'mensagem' => 'Falha ao adicionar o item',
            'data'     => []
        ];
    }

    $stmt->close();
    $conexao->close();
    header("Content-type: application/json; charset=utf-8");
    echo json_encode($retorno);
?>