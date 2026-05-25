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

    $titulo = trim($_POST['titulo'] ?? '');

    if ($titulo === '') {
        echo json_encode([
            'status' => 'nok',
            'mensagem' => 'O título da lista é obrigatório',
            'data' => []
        ]);
        exit;
    }

    // preparando inserção no banco de dados

    $stmt = $conexao->prepare("INSERT INTO lista_compras(titulo, id_usuario) VALUES(?,?)");

    $stmt->bind_param("ss", $titulo, $id_usuario);
    // inserindo
    $stmt->execute();

    if($stmt->affected_rows > 0){
        $retorno = [
            'status' => 'ok', //ok ou nok
            'mensagem' => 'Lista de compras criada com sucesso', //mensagem que envio para o front
            'data' => []
        ];
    }else{
        $retorno = [
            'status' => 'nok', //ok ou nok
            'mensagem' => 'Falha ao inserir a lista de compras', //mensagem que envio para o front
            'data' => []
        ];
    }

    $stmt->close();
    $conexao->close();

    header("Content-type:application/json;charset:utf-8");
    echo json_encode($retorno);
