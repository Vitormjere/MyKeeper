<?php
    session_start();
    include_once(__DIR__ . '/../../config/headers.php');
    include_once(__DIR__ . '/../../config/conexao.php');

    if (empty($_SESSION['usuario']['id'])) {
        echo json_encode([
            'status' => 'nok',
            'mensagem' => 'Usuário não autenticado'
        ]);
        exit;
    }

    $id = $_SESSION['usuario']['id'];
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $cepInput = trim($_POST['cep'] ?? '');
    $cepNumerico = preg_replace('/\D/', '', $cepInput);

    if ($nome === '' || $email === '' || strlen($cepNumerico) !== 8) {
        echo json_encode([
            'status' => 'nok',
            'mensagem' => 'Informe nome, e-mail e um CEP válido no formato 00000-000.'
        ]);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            'status' => 'nok',
            'mensagem' => 'Digite um e-mail válido.'
        ]);
        exit;
    }

    $cep = substr($cepNumerico, 0, 5) . '-' . substr($cepNumerico, 5, 3);

    $stmt = $conexao->prepare("UPDATE usuario SET nome = ?, email = ?, cep = ? WHERE id = ?");
    $stmt->bind_param('sssi', $nome, $email, $cep, $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $_SESSION['usuario']['nome'] = $nome;
        $retorno = [
            'status' => 'ok',
            'mensagem' => 'Perfil atualizado com sucesso'
        ];
    } else {
        $retorno = [
            'status' => 'nok',
            'mensagem' => 'Falha ao atualizar perfil'
        ];
    }

    $stmt->close();
    $conexao->close();
    header('Content-type: application/json; charset=utf-8');
    echo json_encode($retorno);