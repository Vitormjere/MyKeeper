<?php
include_once(__DIR__ . '/../../config/headers.php');
include_once(__DIR__ . '/../../config/conexao.php');
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

$retorno = [
    'status' => '',
    'mensagem' => '',
    'data' => []
];

$id_usuario = $_SESSION['usuario']['id'];

if(isset($_GET['id'])){

    // 1. Verifica se o produto está sendo usado como ingrediente
    $stmt = $conexao->prepare("SELECT COUNT(*) AS total FROM item_ingrediente WHERE id_produto = ?");
    $stmt->bind_param('i', $_GET['id']);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $linha = $resultado->fetch_assoc();
    $stmt->close();

    if($linha['total'] > 0){
        $retorno = [
            'status' => 'nok',
            'mensagem' => 'Não é possível excluir este produto pois ele está sendo usado como ingrediente em uma ou mais receitas.',
            'data' => []
        ];
        $conexao->close();
        header("Content-type:application/json;charset:utf-8");
        echo json_encode($retorno);
        exit();
    }

    // 2. Busca a imagem antes de deletar
    $stmt = $conexao->prepare("SELECT imagem FROM produto WHERE id = ? AND id_usuario = ?");
    $stmt->bind_param('ii', $_GET['id'], $id_usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $imagem = null;
    if($resultado->num_rows > 0){
        $linha = $resultado->fetch_assoc();
        $imagem = $linha['imagem'];
    }
    $stmt->close();

    if($imagem){
        $caminhoFisico = dirname(__DIR__, 2) . str_replace('/mykeeper', '', $imagem);
        if(file_exists($caminhoFisico)){
            unlink($caminhoFisico);
        }
    }

    // 3. Deleta o produto
    $stmt = $conexao->prepare("DELETE FROM produto WHERE id = ? AND id_usuario = ?");
    $stmt->bind_param('ii', $_GET['id'], $id_usuario);
    $stmt->execute();

    if($stmt->affected_rows > 0){
        $retorno = [
            'status' => 'ok',
            'mensagem' => 'Produto excluído com sucesso.',
            'data' => []
        ];
    } else {
        $retorno = [
            'status' => 'nok',
            'mensagem' => 'Produto não encontrado ou sem permissão para exclusão.',
            'data' => []
        ];
    }
    $stmt->close();

} else {
    $retorno = [
        'status' => 'nok',
        'mensagem' => 'É necessário informar um ID para exclusão.',
        'data' => []
    ];
}

$conexao->close();
header("Content-type:application/json;charset:utf-8");
echo json_encode($retorno);