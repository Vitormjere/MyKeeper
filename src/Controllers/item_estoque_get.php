<?php
    include_once(__DIR__ . '/../../config/headers.php');
    include_once(__DIR__ . '/../../config/conexao.php');
    session_start();

    $retorno = [
        'status'   => '',
        'mensagem' => '',
        'data'     => []
    ];

    $id_usuario = $_SESSION['usuario']['id'];

    if(isset($_GET['id'])){
        // Busca item específico 
        $stmt = $conexao->prepare("
            SELECT 
                ie.id,
                ie.quantidade,
                ie.data_validade,
                ie.marca,
                p.nome,
                p.und_medida,
                p.imagem
            FROM item_estoque ie
            INNER JOIN produto p ON p.id = ie.id_produto
            WHERE ie.id = ?
            AND p.id_usuario = ?
        ");
        $stmt->bind_param('ii', $_GET['id'], $id_usuario);

    } elseif(isset($_GET['id_estoque'])){
        // Busca todos os itens de um estoque
        $stmt = $conexao->prepare("
            SELECT 
                ie.id,
                ie.quantidade,
                ie.data_validade,
                ie.marca,
                p.nome,
                p.und_medida,
                p.imagem
            FROM item_estoque ie
            INNER JOIN produto p ON p.id = ie.id_produto
            WHERE ie.id_estoque = ?
            AND p.id_usuario = ?
        ");
        $stmt->bind_param('ii', $_GET['id_estoque'], $id_usuario);

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