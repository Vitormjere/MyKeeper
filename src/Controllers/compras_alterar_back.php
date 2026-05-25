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

    $id_usuario = $_SESSION['usuario']['id'];

    $titulo       = $_POST['titulo'];

    // preparando inserção no banco de dados

    $stmt = $conexao->prepare("UPDATE lista_compras SET titulo = ? WHERE id = ? AND id_usuario = ?");

    $stmt->bind_param("sss", $titulo, $_GET['id'], $id_usuario);
    // inserindo
    $stmt->execute();

    if($stmt->affected_rows > 0){
        $retorno = [
            'status' => 'ok', //ok ou nok
            'mensagem' => 'Lista de compras atualizada com sucesso', //mensagem que envio para o front
            'data' => []
        ];
    }else{
        $retorno = [
            'status' => 'nok', //ok ou nok
            'mensagem' => 'Nenhuma alteração realizada', //mensagem que envio para o front
            'data' => []
        ];
    }

    $stmt->close();
    $conexao->close();

    header("Content-type:application/json;charset:utf-8");
    echo json_encode($retorno);