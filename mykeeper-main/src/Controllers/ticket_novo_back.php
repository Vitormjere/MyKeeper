<?php
    session_start();
    include_once(__DIR__ . '/../../config/headers.php');
    include_once(__DIR__ . '/../../config/conexao.php');
    
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $id_usuario = $_SESSION['usuario']['id'];

    $stmt = $conexao->prepare("INSERT INTO ticket_suporte (titulo, descricao, id_usuario) VALUES (?,?,?)");
    $stmt->bind_param("ssi", $titulo, $descricao, $id_usuario);

    if ($stmt->execute()) {
        $retorno = [
            'status' => 'ok',
            'mensagem' => 'Ticket inserido com sucesso'
        ];
    } else {
        $retorno = [
        'status' => 'nok',
        'mensagem' => 'Falha ao criar ticket: ' 
    ];
    }

    $stmt->close();
    $conexao->close();

    header('Content-type:application/json;charset:utf-8');
    echo json_encode($retorno);
?>