<?php

    session_start();

    include_once '../../config/headers.php';
    include_once '../../config/conexao.php';

    header('Content-Type: application/json; charset=utf-8');

    $retorno = array();

    if (!isset($_SESSION['usuario']['id'])) {

        $retorno['status'] = 'nok';

        echo json_encode($retorno);
        exit;
    }

    $id_usuario = $_SESSION['usuario']['id'];
    $nome = $_GET['nome'] ?? '';

    $sql = "SELECT * FROM produto_historico WHERE id_usuario = ? AND nome = ? LIMIT 1";

    $stmt = $conexao->prepare($sql);

    $stmt->bind_param("is", $id_usuario, $nome);
    $stmt->execute();

    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {

        $dados = $resultado->fetch_assoc();

        $retorno['status'] = 'ok';
        $retorno['data'] = $dados;

    } else {

        $retorno['status'] = 'nok';
        $retorno['data'] = [];
    }

    echo json_encode($retorno);

$stmt->close();
$conexao->close();