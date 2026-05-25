<?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    include_once(__DIR__ . '/../../config/headers.php');
    include_once(__DIR__ . '/../../config/conexao.php');

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if (empty($_SESSION['usuario']['id'])) {
        echo json_encode(['status' => 'nok', 'mensagem' => 'Usuário não autenticado']);
        exit;
    }

    $id   = $_SESSION['usuario']['id'];
    $tipo = $_SESSION['usuario']['tipo'];

    $senhaDigitada = $_POST['senha']      ?? '';
    $novaSenha     = $_POST['nova_senha'] ?? '';

    if (!$senhaDigitada || !$novaSenha) {
        echo json_encode(['status' => 'nok', 'mensagem' => 'Preencha todos os campos']);
        exit;
    }

    // 1. Busca o hash atual do banco
    $tabela = ($tipo == 0) ? 'usuario' : 'suporte';

    $stmt = $conexao->prepare("SELECT senha FROM $tabela WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 0) {
        echo json_encode(['status' => 'nok', 'mensagem' => 'Usuário não encontrado']);
        exit;
    }

    $hashAtual = $resultado->fetch_assoc()['senha'];
    $stmt->close();

    // 2. Verifica se a senha atual está correta
    if (!password_verify($senhaDigitada, $hashAtual)) {
        echo json_encode(['status' => 'nok', 'mensagem' => 'Senha atual incorreta']);
        exit;
    }

    // 3. Atualiza com o novo hash
    $novoHash = password_hash($novaSenha, PASSWORD_DEFAULT);

    $stmt = $conexao->prepare("UPDATE $tabela SET senha = ? WHERE id = ?");
    $stmt->bind_param('si', $novoHash, $id);

    try {
        $stmt->execute();
        $retorno = ($stmt->affected_rows > 0)
            ? ['status' => 'ok',  'mensagem' => 'Senha alterada com sucesso']
            : ['status' => 'nok', 'mensagem' => 'Nenhuma alteração realizada'];
    } catch (mysqli_sql_exception $e) {
        $retorno = ['status' => 'nok', 'mensagem' => 'Falha ao atualizar: ' . $e->getMessage()];
    }

    $stmt->close();
    $conexao->close();

header('Content-Type: application/json; charset=utf-8');
echo json_encode($retorno);