<?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    session_start();
    include_once(__DIR__ . '/../../config/headers.php');
    include_once(__DIR__ . '/../../config/conexao.php');
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    if (empty($_SESSION['usuario']['id'])) {
        echo json_encode([
            'status' => 'nok',
            'mensagem' => 'Usuário não autenticado'
        ]);
        exit;
    }

    $id = $_SESSION['usuario']['id'];
    $tipo = $_SESSION['usuario']['tipo'];
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

    if($tipo == 0){
        $stmt = $conexao->prepare("UPDATE usuario SET nome = ?, email = ?, cep = ? WHERE id = ?");
        $stmt->bind_param('sssi', $nome, $email, $cep, $id);
    }else{
        $stmt = $conexao->prepare("UPDATE suporte SET nome = ?, email = ?, cep = ? WHERE id = ?");
        $stmt->bind_param('sssi', $nome, $email, $cep, $id);
    }
    

    try {
        $stmt->execute(); 
        if($stmt->affected_rows > 0){
            $_SESSION['usuario']['nome'] = $nome; 
            $retorno = [
                'status'   => 'ok',
                'mensagem' => 'Perfil atualizado com sucesso',
                'data'     => ['nome' => $nome]  
            ];
        }
    } catch (mysqli_sql_exception $e) {
        $retorno = [
            'status'   => 'nok',
            'mensagem' => $e->getCode() == 1062
                ? 'Este e-mail já está cadastrado por outro usuário'
                : 'Falha ao atualizar: ' . $e->getMessage()
        ];
    }

    $stmt->close();
    $conexao->close();
    header('Content-type: application/json; charset=utf-8');
    echo json_encode($retorno);