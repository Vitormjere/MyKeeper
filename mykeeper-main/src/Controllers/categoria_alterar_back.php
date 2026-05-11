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

    $nome_categoria      = $_POST['nome_categoria'];
    $descricao_categoria = $_POST['descricao_categoria'];

    $nome_arquivo = null;

    // VERIFICA SE VEIO IMAGEM
    if(isset($_FILES['icone_categoria']) && $_FILES['icone_categoria']['error'] == 0){

        $ext = pathinfo($_FILES['icone_categoria']['name'], PATHINFO_EXTENSION);
        $nome_arquivo = uniqid('categoria_') . '.' . $ext;

        $pastaFisica = dirname(__DIR__, 2) . '/public/uploads/categorias/';
        $caminhoURL = '../../public/uploads/categorias/' . $nome_arquivo;

        move_uploaded_file(
            $_FILES['icone_categoria']['tmp_name'],
            $pastaFisica . $nome_arquivo
        );
    }

    if($nome_arquivo){
        $stmt = $conexao->prepare("
            UPDATE categoria 
            SET nome=?, descricao=?, icone=?
            WHERE id=? AND id_usuario = ?
        ");
        $stmt->bind_param("sssss", $nome_categoria, $descricao_categoria, $caminhoURL, $_GET['id'], $id_usuario);
    } else {
        $stmt = $conexao->prepare("
            UPDATE categoria 
            SET nome=?, descricao=?
            WHERE id=? AND id_usuario = ?
        ");
        $stmt->bind_param("ssss", $nome_categoria, $descricao_categoria, $_GET['id'], $id_usuario);
    }

    $stmt->execute();

    if($stmt->affected_rows > 0){
        $retorno = [
            'status' => 'ok',
            'mensagem' => 'Categoria alterada com sucesso',
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
