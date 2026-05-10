<?php
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

    $id_usuario = $_SESSION['usuario']['id'];

    if(isset($_GET['id'])){
        $stmt = $conexao->prepare("
            DELETE ie FROM item_estoque ie
            INNER JOIN produto p ON p.id = ie.id_produto
            WHERE ie.id = ?
            AND p.id_usuario = ?
        ");
        $stmt->bind_param('ii', $_GET['id'], $id_usuario);
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