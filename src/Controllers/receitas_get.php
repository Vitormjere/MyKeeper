<?php
session_start();
include_once(__DIR__ . '/../../config/conexao.php');

header('Content-Type: application/json');

// Verifica autenticação
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Não autorizado.']);
    exit();
}

// Valida o parâmetro
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'ID inválido.']);
    exit();
}

$id_receita = (int) $_GET['id'];
$id_usuario = (int) $_SESSION['usuario_id'];

try {
    // Busca dados da receita — garante que pertence ao usuário logado
    $stmt = $conexao->prepare("
        SELECT id, titulo, descricao, gerada_por_ia, data_geracao
        FROM receita
        WHERE id = ? AND id_usuario = ?
    ");
    $stmt->bind_param('ii', $id_receita, $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Receita não encontrada.']);
        exit();
    }

    $receita = $result->fetch_assoc();

    // Busca ingredientes da receita
    $stmt2 = $conexao->prepare("
        SELECT p.nome AS produto, ii.qtd, ii.und_medida
        FROM item_ingrediente ii
        INNER JOIN produto p ON p.id = ii.id_produto
        WHERE ii.id_receita = ?
    ");
    $stmt2->bind_param('i', $id_receita);
    $stmt2->execute();
    $result2 = $stmt2->get_result();

    $ingredientes = [];
    while ($row = $result2->fetch_assoc()) {
        $ingredientes[] = $row;
    }

    $receita['gerada_por_ia'] = (bool) $receita['gerada_por_ia'];
    $receita['ingredientes'] = $ingredientes;

    echo json_encode(['sucesso' => true, 'receita' => $receita]);

} catch (Exception $e) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro interno.']);
}