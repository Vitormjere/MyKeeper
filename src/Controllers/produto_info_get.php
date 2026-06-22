<?php
include_once(__DIR__ . '/../../config/headers.php');
include_once(__DIR__ . '/../../config/conexao.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$id_usuario = $_SESSION['usuario']['id'];
$nome = trim($_GET['nome'] ?? '');

if ($nome === '') {
    echo json_encode(['status' => 'nok', 'mensagem' => 'Nome não informado']);
    exit;
}

$stmt = $conexao->prepare("
    SELECT id_categoria, und_medida 
    FROM produto 
    WHERE LOWER(TRIM(nome)) = LOWER(TRIM(?)) AND id_usuario = ?
    LIMIT 1
");
$stmt->bind_param('si', $nome, $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $dados = $result->fetch_assoc();
    echo json_encode(['status' => 'ok', 'data' => $dados]);
} else {
    echo json_encode(['status' => 'nok', 'mensagem' => 'Produto não encontrado']);
}

$stmt->close();
$conexao->close();