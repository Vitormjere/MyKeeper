<?php
    ini_set('display_errors', 0);
    error_reporting(0);
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    } 
    include_once(__DIR__ . '/../../config/headers.php');
    include_once(__DIR__ . '/../../config/conexao.php');

    $id_usuario = $_SESSION['usuario']['id'];
    $nome = $_GET['nome'] ?? '';

    $stmt = $conexao->prepare("SELECT id FROM produto WHERE nome = ? AND id_usuario = ?");
    $stmt->bind_param('si', $nome, $id_usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if($resultado->num_rows > 0){
        echo json_encode(['status' => 'ok']);
    } else {
        echo json_encode(['status' => 'nok']);
    }

    $stmt->close();
    $conexao->close();
?>