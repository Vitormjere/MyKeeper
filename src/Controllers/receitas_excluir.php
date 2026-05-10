<?php
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

if (isset($_GET['id'])) {

    $id_receita = (int) $_GET['id'];

    $stmt = $conexao->prepare("DELETE FROM receita WHERE id = ? AND id_usuario = ?");
    $stmt->bind_param('ii', $id_receita, $id_usuario);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $retorno = [
            'status'   => 'ok',
            'mensagem' => 'Receita excluída com sucesso.',
            'data'     => []
        ];
    } else {
        $retorno = [
            'status'   => 'nok',
            'mensagem' => 'Receita não encontrada.',
            'data'     => []
        ];
    }

    $stmt->close();

} else {
    $retorno = [
        'status'   => 'nok',
        'mensagem' => 'É necessário informar um ID para exclusão.',
        'data'     => []
    ];
}

$conexao->close();

header("Content-type: application/json; charset=utf-8");
echo json_encode($retorno);