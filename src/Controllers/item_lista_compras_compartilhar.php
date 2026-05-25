<?php
    include_once(__DIR__ . '/../../config/headers.php');
    include_once(__DIR__ . '/../../config/conexao.php');

    $token = $_GET['token'] ?? '';

    if(empty($token)){
        echo json_encode(['status' => 'nok', 'mensagem' => 'Token inválido']);
        exit;
    }

    // Busca a lista pelo token sem exigir sessão
    $stmt = $conexao->prepare("SELECT id, titulo, status_compra, data_criacao FROM lista_compras WHERE link_compart = ?");
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $lista = $stmt->get_result()->fetch_assoc();

    if(!$lista){
        echo json_encode(['status' => 'nok', 'mensagem' => 'Lista não encontrada']);
        exit;
    }

    // Busca os itens
    $stmt = $conexao->prepare("
        SELECT il.quantidade, p.nome, p.und_medida, c.nome AS nome_categoria
        FROM item_lista_compra il
        INNER JOIN produto p ON p.id = il.id_produto
        INNER JOIN categoria c ON c.id = p.id_categoria
        WHERE il.id_lista_compra = ?
        ORDER BY p.nome ASC
    ");
    $stmt->bind_param('i', $lista['id']);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $itens = [];
    while($linha = $resultado->fetch_assoc()){
        $itens[] = $linha;
    }

    echo json_encode([
        'status'  => 'ok',
        'lista'   => $lista,
        'data'    => $itens
    ]);

    $stmt->close();
    $conexao->close();
?>