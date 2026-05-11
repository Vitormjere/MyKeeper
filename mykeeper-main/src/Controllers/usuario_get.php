<?php
    include_once(__DIR__ . '/../../config/headers.php');
    include_once(__DIR__ . '/../../config/conexao.php');

    $retorno = [
        'status' => '',
        'mensagem' => '',
        'data' => []
    ];

    if(isset($_GET['id'])){
        $stmt = $conexao->prepare("SELECT id, nome, cep, email FROM usuario WHERE id = ?");
        $stmt->bind_param('i', $_GET['id']);
    } else {
        echo json_encode([
            'status' => 'nok',
            'mensagem' => 'ID do usuário não fornecido',
            'data' => []
        ]);
        exit;
    }

    $stmt->execute();
    $resultado = $stmt->get_result();
    $tabela = [];

    if($resultado->num_rows > 0){
        $retorno = [
            'status' => 'ok',
            'mensagem' => 'Sucesso, consulta efetuada',
            'data' => $resultado->fetch_assoc() 
        ];
    } else {
        $retorno = [
            'status' => 'nok',
            'mensagem' => 'Não há registros',
            'data' => []
        ];
    }

    $stmt->close();
    $conexao->close();

    header("Content-type: application/json; charset=utf-8");
    echo json_encode($retorno);
?>