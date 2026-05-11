<?php
    include_once(__DIR__ . '/../../config/headers.php');
    include_once(__DIR__ . '/../../config/conexao.php');

    $retorno = [
        'status' => '',
        'mensagem' => '',
        'data' => []
    ];

    if(isset($_GET['id'])){
        $stmt = $conexao->prepare("
            SELECT ts.*, u.nome as usuario_nome 
            FROM ticket_suporte ts 
            JOIN usuario u ON ts.id_usuario = u.id 
            WHERE ts.id = ?"
        );

        $stmt->bind_param('i', $_GET['id']);
        $stmt->execute();

        $resultado = $stmt->get_result();

        if($resultado->num_rows > 0){
            $retorno = [
                'status' => 'ok', 
                'mensagem' => 'Sucesso', 
                'data' => $resultado->fetch_assoc()
            ]; 

        } else {
            $retorno = [
                'status' => 'nok', 
                'mensagem' => 'Não há registros', 
                'data' => []
            ];
        }
    } else {
        $stmt = $conexao->prepare("
            SELECT ts.*, u.nome as usuario_nome 
            FROM ticket_suporte ts 
            JOIN usuario u ON ts.id_usuario = u.id"
        );

        $stmt->execute();
        $resultado = $stmt->get_result();

        $tabela = [];

        while($linha = $resultado->fetch_assoc()){
            $tabela[] = $linha;
        }

        if(count($tabela) > 0){
            $retorno = [
                'status' => 'ok',
                'mensagem' => 'Sucesso', 
                'data' => $tabela
            ];

        }else{
            $retorno = [
                'status' => 'ok',
                'mensagem' => 'Não há registros',
                'data' => []
            ];
        }
            
    }

    $stmt->close();
    $conexao->close();

    header("Content-type: application/json; charset=utf-8");
    echo json_encode($retorno);
?>