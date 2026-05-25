<?php
    include_once(__DIR__ . '/../../config/headers.php');
    include_once(__DIR__ . '/../../config/conexao.php');
    
    $nome      = $_POST['nome'];
    $email     = $_POST['email'];
    $senha     = $_POST['senha'];
    $cep      = $_POST['cep'];
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    $stmt = $conexao->prepare("INSERT INTO suporte (nome, email, senha, cep) VALUES (?,?,?,?)");
    $stmt->bind_param("ssss", $nome, $email, $senhaHash, $cep);

    if ($stmt->execute()) {
        $retorno = [
            'status' => 'ok',
            'mensagem' => 'Registro inserido com sucesso'
        ];
    } else {
        $retorno = [
            'status' => 'nok',
            'mensagem' => 'Falha ao inserir: ' . $stmt->error
        ];
    }

    $stmt->close();
    $conexao->close();

    header('Content-type:application/json;charset:utf-8');
    echo json_encode($retorno);
?>