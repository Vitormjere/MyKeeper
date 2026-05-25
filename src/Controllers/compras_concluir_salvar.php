<?php

    include_once(__DIR__ . '/../../config/headers.php');
    include_once(__DIR__ . '/../../config/conexao.php');
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $retorno    = [
        'status' => '', 
        'mensagem' => ''
    ];

    $id_usuario = $_SESSION['usuario']['id'];

    $id_lista_compra = intval($_POST['id_lista_compra'] ?? 0);
    $id_estoque      = intval($_POST['id_estoque']      ?? 0);
    $itens           = json_decode($_POST['itens'] ?? '[]', true);

    if (!$id_lista_compra || !$id_estoque || empty($itens)) {
        echo json_encode([
            'status' => 'erro', 
            'mensagem' => 'Dados inválidos.'
        ]);

        exit;
    }

    // Valida que o estoque pertence ao usuário
    $check = $conexao->prepare("SELECT id FROM estoque WHERE id = ? AND id_usuario = ?");
    $check->bind_param('ii', $id_estoque, $id_usuario);
    $check->execute();
    $check->store_result();

    if ($check->num_rows === 0) {
        echo json_encode([
            'status' => 'erro', 
            'mensagem' => 'Estoque não encontrado.'
        ]);
        exit;
    }
    $check->close();

    // Valida que a lista pertence ao usuário
    $checkLista = $conexao->prepare("SELECT id FROM lista_compras WHERE id = ? AND id_usuario = ?");
    $checkLista->bind_param('ii', $id_lista_compra, $id_usuario);
    $checkLista->execute();
    $checkLista->store_result();

    if ($checkLista->num_rows === 0) {
        echo json_encode([
            'status' => 'erro', 
            'mensagem' => 'Lista não encontrada.'
        ]);
        exit;
    }
    $checkLista->close();

    foreach ($itens as $item) {
        $id_produto    = intval($item['id_produto']);
        $quantidade    = floatval($item['quantidade'] ?: 0);
        $data_validade = $item['data_validade'] ?: null;
        $marca         = $item['marca'] ?: null;

        $checkItem = $conexao->prepare("
            SELECT id FROM item_estoque
            WHERE id_estoque = ? AND id_produto = ?
            LIMIT 1
        ");
        $checkItem->bind_param('ii', $id_estoque, $id_produto);
        $checkItem->execute();
        $itemExistente = $checkItem->get_result()->fetch_assoc();
        $checkItem->close();

        if ($itemExistente) {
            $stmt = $conexao->prepare("
                UPDATE item_estoque
                SET quantidade = COALESCE(quantidade, 0) + ?,
                    data_validade = COALESCE(?, data_validade),
                    marca = COALESCE(?, marca)
                WHERE id = ?
            ");
            $stmt->bind_param('dssi', $quantidade, $data_validade, $marca, $itemExistente['id']);
        } else {
            $stmt = $conexao->prepare("
                INSERT INTO item_estoque (id_estoque, id_produto, quantidade, data_validade, marca)
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->bind_param('iidss', $id_estoque, $id_produto, $quantidade, $data_validade, $marca);
        }

        $stmt->execute();
        $stmt->close();
    }

    // Atualiza o status da lista para concluida
    $update = $conexao->prepare("UPDATE lista_compras SET status_compra = 'concluida' WHERE id = ? AND id_usuario = ?");
    $update->bind_param('ii', $id_lista_compra, $id_usuario);
    $update->execute();
    $update->close();

    $conexao->close();

    echo json_encode([
        'status' => 'ok', 
        'mensagem' => 'Lista concluída e itens adicionados ao estoque.'
    ]);
?>
