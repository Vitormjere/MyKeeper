<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
    include_once(__DIR__ . '/../../config/headers.php');
    include_once(__DIR__ . '/../../config/conexao.php');
    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }

    $retorno = [
        'status'   => '',
        'mensagem' => '',
        'data'     => []
    ];

    $id_usuario  = $_SESSION['usuario']['id'];
    $id_lista_compra = $_GET['id_lista_compra'];
    $id_produto     = $_GET['id_produto'];

    if(isset($_GET['id_lista_compra']) && isset($_GET['id_produto'])){
        $quantidade    = $_POST['quantidade']    ?: null;

        // Garante que o item pertence ao usuário via JOIN
        $stmt = $conexao->prepare("
            UPDATE item_lista_compra ilc
            INNER JOIN produto p ON p.id = ilc.id_produto
            SET ilc.quantidade    = ?
            WHERE ilc.id_lista_compra = ? AND ilc.id_produto = ?
            AND p.id_usuario = ?
        ");
        $stmt->bind_param('dsss', $quantidade, $id_lista_compra, $id_produto, $id_usuario);
        $stmt->execute();

        if($stmt->affected_rows > 0){
            $retorno = [
                'status'   => 'ok',
                'mensagem' => 'Item alterado com sucesso',
                'data'     => []
            ];
        } else {
            $retorno = [
                'status'   => 'nok',
                'mensagem' => 'Nenhuma alteração realizada',
                'data'     => []
            ];
        }
        $stmt->close();
    } else {
        $retorno = [
            'status'   => 'nok',
            'mensagem' => 'ID não informado',
            'data'     => []
        ];
    }

    $conexao->close();
    header("Content-type: application/json; charset=utf-8");
    echo json_encode($retorno);
?>