<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
    include_once(__DIR__ . '/../../config/headers.php');
    include_once(__DIR__ . '/../../config/conexao.php');
    if(session_status() === PHP_SESSION_NONE){
        session_start();
    };

    $retorno = [
        'status'   => '',
        'mensagem' => '',
        'data'     => []
    ];

    $id_lista_compra = $_GET['id_lista_compra'];
    $id_produto     = $_GET['id_produto'];
    $id_usuario = $_SESSION['usuario']['id'];

    if (isset($_GET['id_lista_compra']) && isset($_GET['id_produto'])) {

        $stmt = $conexao->prepare("
            DELETE ilc FROM item_lista_compra ilc
            INNER JOIN lista_compras lc ON lc.id = ilc.id_lista_compra
            WHERE ilc.id_lista_compra = ?
            AND ilc.id_produto = ?
            AND lc.id_usuario = ?
        ");
        $stmt->bind_param("sss", $id_lista_compra, $id_produto, $id_usuario);
        $stmt->execute();

        if($stmt->affected_rows > 0){
            $retorno = [
                'status'   => 'ok',
                'mensagem' => 'Item excluído com sucesso',
                'data'     => []
            ];
        } else {
            $retorno = [
                'status'   => 'nok',
                'mensagem' => 'Falha ao excluir o item',
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