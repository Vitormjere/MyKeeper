<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
    include_once(__DIR__ . '/../../config/headers.php');
    include_once(__DIR__ . '/../../config/conexao.php');
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    } 

    $retorno = [
        'status'   => '',
        'mensagem' => '',
        'data'     => []
    ];

    $id_usuario   = $_SESSION['usuario']['id'];
    $id_lista_compra   = $_POST['id_lista_compra'];
    $id_produto   = $_POST['id_produto'];
    $quantidade   = $_POST['quantidade']   ?: null;

    // Garante que a lista de compras pertence ao usuário
    $check = $conexao->prepare("SELECT id FROM lista_compras WHERE id = ? AND id_usuario = ?");
    $check->bind_param('ss', $id_lista_compra, $id_usuario);
    $check->execute();
    $check->store_result();

    if ($check->num_rows === 0) {
        echo json_encode([
            'status'   => 'nok',
            'mensagem' => 'Lista de compras não encontrada',
            'data'     => []
        ]);
        exit;
    }
    $check->close();

    $stmt = $conexao->prepare("
        INSERT INTO item_lista_compra (id_lista_compra, id_produto, quantidade)
        VALUES (?, ?, ?)
    ");
    $stmt->bind_param('ssd', $id_lista_compra, $id_produto, $quantidade);

    try {
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $retorno = ['status' => 'ok', 'mensagem' => 'Item adicionado com sucesso', 'data' => []];
        } else {
            $retorno = ['status' => 'nok', 'mensagem' => 'Falha ao adicionar o item', 'data' => []];
        }
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() === 1062) {
            $retorno = ['status' => 'nok', 'mensagem' => 'Item já existe nesta lista de compra', 'data' => []];
        } else {
            $retorno = ['status' => 'nok', 'mensagem' => 'Erro ao adicionar o item', 'data' => []];
        }
    }

    $stmt->close();
    $conexao->close();
    header("Content-type: application/json; charset=utf-8");
    echo json_encode($retorno);
?>