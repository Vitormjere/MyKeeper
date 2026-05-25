<?php
    include_once(__DIR__ . '/../../config/headers.php');
    include_once(__DIR__ . '/../../config/conexao.php');

    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }

    $retorno = [
        'status' => '', //ok ou nok
        'mensagem' => '', //mensagem que envio para o front
        'data' => []
    ];

    if(isset($_GET['id'])){
        $id_usuario = $_SESSION['usuario']['id'];

        $stmt = $conexao->prepare("
            DELETE FROM ticket_suporte
            WHERE id = ?
            AND id_usuario = ?
            AND status_ticket = 'ticket_aberto'
        ");
        $stmt->bind_param('ii', $_GET['id'], $id_usuario);
        $stmt->execute();

        if($stmt->affected_rows > 0){
            $retorno = [
                'status' => 'ok', //ok ou nok
                'mensagem' => 'Ticket excluido', //mensagem que envio para o front
                'data' => []
            ];
        }else{
            $retorno = [
                'status' => 'nok', //ok ou nok
                'mensagem' => 'Apenas tickets em aberto podem ser excluídos', //mensagem que envio para o front
                'data' => []
            ];
        }

        $stmt->close();
    }else{
        $retorno = [
            'status' => 'nok', //ok ou nok
            'mensagem' => 'É necessário informar um ID para exclusão', //mensagem que envio para o front
            'data' => []
        ];
    };

    $conexao->close();

    header("Content-type:application/json;charset:utf-8");
    echo json_encode($retorno);
