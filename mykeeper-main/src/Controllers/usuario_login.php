<?php
session_start();
include_once(__DIR__ . '/../../config/headers.php');
include_once(__DIR__ . '/../../config/conexao.php');

$stmt = $conexao->prepare("SELECT * FROM usuario WHERE email = ?");
$stmt->bind_param("s", $_POST['email']);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

if ($usuario && password_verify($_POST['senha'], $usuario['senha'])) {
    if (!$usuario['conta_ativa']) {
        echo json_encode([
            'status' => 'nok',
            'mensagem' => 'Conta desativada. Entre em contato com o suporte.'
        ]);
        exit;
    }

    session_start();
    $_SESSION['usuario'] = [
        'id'   => $usuario['id'],
        'nome' => $usuario['nome']
    ];
    $_SESSION['logado'] = true;
    $retorno = [
        'status'   => 'ok',
        'mensagem' => 'Login realizado com sucesso',
        'redirect' => 'home.php'
    ];
    
}else {
    $retorno = [
        'status'   => 'nok',
        'mensagem' => 'Email ou senha incorretos'
    ];
}

$stmt->close();
$conexao->close();

header('Content-type:application/json;charset:utf-8');
echo json_encode($retorno);

?>
