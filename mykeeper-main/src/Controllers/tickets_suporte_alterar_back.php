<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
include_once(__DIR__ . '/../../config/headers.php');
include_once(__DIR__ . '/../../config/conexao.php');

$retorno = [
    'status' => '',
    'mensagem' => '',
    'data' => []
];

if(isset($_GET['id'])){

    $resposta_ticket = $_POST['resposta_ticket'];
    $status_ticket   = $_POST['status_ticket'];

    $resposta_ticket = $_POST['resposta_ticket'];
    $status_ticket   = $_POST['status_ticket'];

    $stmt = $conexao->prepare("UPDATE ticket_suporte SET resposta_ticket=?, status_ticket=? WHERE id=?");
    $stmt->bind_param("ssi", $resposta_ticket, $status_ticket, $_GET['id']);
    $stmt->execute();

    if($stmt->affected_rows > 0){
        $retorno = [
            'status' => 'ok',
            'mensagem' => 'Ticket alterado com sucesso',
            'data' => []
        ];
    }else{
        $retorno = [
            'status' => 'nok',
            'mensagem' => 'Nenhuma alteração realizada',
            'data' => []
        ];
    }

    $stmt->close();

}else{
    $retorno = [
        'status' => 'nok',
        'mensagem' => 'ID não informado',
        'data' => []
    ];
}

$conexao->close();

header("Content-type: application/json; charset=utf-8");
echo json_encode($retorno);