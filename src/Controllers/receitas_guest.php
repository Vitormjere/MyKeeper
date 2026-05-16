<?php
include_once(__DIR__ . '/../../config/headers.php');
include_once(__DIR__ . '/../../config/conexao.php');

$token = $_GET['token'] ?? '';

if(empty($token)){
    echo json_encode(['status' => 'nok', 'mensagem' => 'Token inválido']);
    exit;
}

$stmt = $conexao->prepare("SELECT id, titulo, descricao, gerada_por_ia, data_geracao FROM receita WHERE link_compart = ?");
$stmt->bind_param('s', $token);
$stmt->execute();
$receita = $stmt->get_result()->fetch_assoc();

if(!$receita){
    echo json_encode(['status' => 'nok', 'mensagem' => 'Receita não encontrada']);
    exit;
}

$receita['gerada_por_ia'] = (bool) $receita['gerada_por_ia'];

$stmt = $conexao->prepare("
    SELECT p.nome, p.und_medida, ii.qtd
    FROM item_ingrediente ii
    INNER JOIN produto p ON p.id = ii.id_produto
    WHERE ii.id_receita = ?
");
$stmt->bind_param('i', $receita['id']);
$stmt->execute();
$result = $stmt->get_result();

$ingredientes = [];
while($row = $result->fetch_assoc()){
    $ingredientes[] = $row;
}

$receita['ingredientes'] = $ingredientes;

echo json_encode([
    'status' => 'ok',
    'data'   => $receita
]);

$stmt->close();
$conexao->close();
?>