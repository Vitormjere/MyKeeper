<?php
    include_once(__DIR__ . '/../../config/headers.php');
    include_once(__DIR__ . '/../../config/conexao.php');
    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }
    $retorno = [
        'status'   => '',
        'mensagem' => '',
        'data'     => []
    ];

    $id_usuario = $_SESSION['usuario']['id'];

    if(isset($_GET['id_lista_compra'])){
        // Busca todos os itens de uma lista de compra
        $stmt = $conexao->prepare("
            SELECT
                il.id_lista_compra,
                il.id_produto,
                il.quantidade,
                p.nome,
                p.und_medida
            FROM item_lista_compra il
            INNER JOIN produto p ON p.id = il.id_produto
            INNER JOIN lista_compras lc ON lc.id = il.id_lista_compra
            WHERE il.id_lista_compra = ?
            AND lc.id_usuario = ?
            ORDER BY p.nome ASC
        ");
        $stmt->bind_param('ii', $_GET['id_lista_compra'], $id_usuario);

    } elseif(isset($_GET['id_lista_compra']) && isset($_GET['id_produto'])){
        // Busca item específico pela chave composta
        $stmt = $conexao->prepare("
            SELECT
                il.id_lista_compra,
                il.id_produto,
                il.quantidade,
                p.nome,
                p.und_medida
            FROM item_lista_compra il
            INNER JOIN produto p ON p.id = il.id_produto
            INNER JOIN lista_compras lc ON lc.id = il.id_lista_compra
            WHERE il.id_lista_compra = ?
            AND il.id_produto = ?
            AND lc.id_usuario = ?
        ");
        $stmt->bind_param('iii', $_GET['id_lista_compra'], $_GET['id_produto'], $id_usuario);

    } else {
        echo json_encode([
            'status'   => 'nok',
            'mensagem' => 'Parâmetro não informado',
            'data'     => []
        ]);
        exit;
    }

    $stmt->execute();
    $resultado = $stmt->get_result();
    $tabela = [];

    if($resultado->num_rows > 0){
        while($linha = $resultado->fetch_assoc()){
            $tabela[] = $linha;
        }
        $retorno = [
            'status'   => 'ok',
            'mensagem' => 'Sucesso, consulta efetuada',
            'data'     => $tabela
        ];
    } else {
        $retorno = [
            'status'   => 'nok',
            'mensagem' => 'Não há registros',
            'data'     => []
        ];
    }

    $stmt->close();
    $conexao->close();
    header("Content-type: application/json; charset=utf-8");
    echo json_encode($retorno);
?>