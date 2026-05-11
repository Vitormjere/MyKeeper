<?php
include_once(__DIR__ . '/../../config/headers.php');
include_once(__DIR__ . '/../../config/conexao.php');

$retorno = [
    'status' => '',
    'mensagem' => '',
    'data' => []
];

if(isset($_GET['id'])){

    $nome      = $_POST['nome'];
    $email     = $_POST['email'];

    $stmt = $conexao->prepare("UPDATE suporte SET nome=?, email=? WHERE id=?");
    $stmt->bind_param("ssi", $nome, $email, $_GET['id']);

    $stmt->execute();

    if($stmt->affected_rows > 0){
        $retorno = [
            'status' => 'ok',
            'mensagem' => 'Suporte alterado com sucesso',
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