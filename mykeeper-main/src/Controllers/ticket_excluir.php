<?php
    include_once(__DIR__ . '/../../config/headers.php');
    include_once(__DIR__ . '/../../config/conexao.php');

    $retorno = [
        'status' => '', //ok ou nok
        'mensagem' => '', //mensagem que envio para o front
        'data' => []
    ];

    if(isset($_GET['id'])){
        $stmt = $conexao->prepare("DELETE FROM ticket_suporte WHERE id = ?");
        $stmt->bind_param('i', $_GET['id']);
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
                'mensagem' => 'Ticket não excluido', //mensagem que envio para o front
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