<?php
    include_once(__DIR__ . '/../../config/headers.php');
    include_once(__DIR__ . '/../../config/conexao.php');
    session_start();

    $retorno = [
        'status' => '',
        'mensagem' => '',
        'data' => []
    ];

    $id_usuario = $_SESSION['usuario']['id'];

    if(isset($_GET['id'])){
        $stmt = $conexao->prepare("
            SELECT p.*, c.nome AS categoria 
            FROM produto p
            LEFT JOIN categoria c ON p.id_categoria = c.id
            WHERE p.id = ? AND p.id_usuario = ?
        ");
        $stmt->bind_param('ii', $_GET['id'], $id_usuario);
    } else {
        $stmt = $conexao->prepare("
            SELECT p.*, c.nome AS categoria 
            FROM produto p
            LEFT JOIN categoria c ON p.id_categoria = c.id
            WHERE p.id_usuario = ?
        ");
        $stmt->bind_param('i', $id_usuario);
    }

    $stmt->execute();
    $resultado = $stmt->get_result();
    $tabela = [];

    if($resultado->num_rows > 0){
        while($linha = $resultado->fetch_assoc()){
            $tabela[] = $linha;
        }
        $retorno = [
            'status' => 'ok',
            'mensagem' => 'Sucesso, consulta efetuada',
            'data' => $tabela
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