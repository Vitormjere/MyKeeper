<?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    include_once(__DIR__ . '/../../config/headers.php');
    include_once(__DIR__ . '/../../config/conexao.php');

    $retorno = [
        'status' => '', //ok ou nok
        'mensagem' => '', //mensagem que envio para o front
        'data' => []
    ];

    if(session_status() == PHP_SESSION_NONE){
        session_start();
    }

    $id = $_SESSION['usuario']['id'];

    if(isset($_GET['id'])){
        if($_GET['id'] != $id){
            $stmt = $conexao->prepare("DELETE FROM suporte WHERE id = ?");
            $stmt->bind_param('i', $_GET['id']);
            $stmt->execute();

            if($stmt->affected_rows > 0){
                $retorno = [
                    'status' => 'ok', //ok ou nok
                    'mensagem' => 'Conta excluida', //mensagem que envio para o front
                    'data' => []
                ];
            }else{
                $retorno = [
                    'status' => 'nok', //ok ou nok
                    'mensagem' => 'Conta não excluida', //mensagem que envio para o front
                    'data' => []
                ];
            }

            $stmt->close();
        }else{
            $retorno = [
                'status' => 'nok', //ok ou nok
                'mensagem' => 'Não é possível excluir a conta que você está logado', //mensagem que envio para o front
                'data' => []
            ];
        }
        
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