<?php
include_once(__DIR__ . '/../../config/headers.php');
include_once(__DIR__ . '/../../config/conexao.php');

if(session_status() === PHP_SESSION_NONE){
    session_start();
};

$retorno = [
    'status' => '',
    'mensagem' => '',
    'data' => []
];

$id_usuario = $_SESSION['usuario']['id'];

if(isset($_GET['id'])){

    $nome_estoque      = $_POST['nome_estoque'];

    $nome_arquivo = null;

    // VERIFICA SE VEIO IMAGEM
    if(isset($_FILES['icone_estoque']) && $_FILES['icone_estoque']['error'] == 0){

        $ext = pathinfo($_FILES['icone_estoque']['name'], PATHINFO_EXTENSION);
        $nome_arquivo = uniqid('estoque_') . '.' . $ext;

        $pastaFisica = dirname(__DIR__, 2) . '/public/uploads/estoques/';
        $caminhoURL = '/mykeeper/public/uploads/estoques/' . $nome_arquivo;

        move_uploaded_file(
            $_FILES['icone_estoque']['tmp_name'],
            $pastaFisica . $nome_arquivo
        );
    }

    if($nome_arquivo){
        $stmt = $conexao->prepare("
            UPDATE estoque 
            SET nome_estoque=?, icone_estoque=?
            WHERE id=? AND id_usuario = ?
        ");
        $stmt->bind_param("ssss", $nome_estoque, $caminhoURL, $_GET['id'], $id_usuario);
    } else {
        $stmt = $conexao->prepare("
            UPDATE estoque 
            SET nome_estoque=?
            WHERE id=? AND id_usuario = ?
        ");
        $stmt->bind_param("sss", $nome_estoque, $_GET['id'], $id_usuario);
    }

    $stmt->execute();

    if($stmt->affected_rows > 0){
        $retorno = [
            'status' => 'ok',
            'mensagem' => 'Estoque alterado com sucesso',
            'data' => []
        ];
    }else{
        $retorno = [
            'status' => 'nok',
            'mensagem' => 'Nenhuma alteração realizada',
            'data' => []
        ];
    }

    $stmt->close();

}else{
    $retorno = [
        'status' => 'nok',
        'mensagem' => 'ID não informado',
        'data' => []
    ];
}

$conexao->close();

header("Content-type: application/json; charset=utf-8");
echo json_encode($retorno);