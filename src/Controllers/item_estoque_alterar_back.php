<?php
    include_once(__DIR__ . '/../../config/headers.php');
    include_once(__DIR__ . '/../../config/conexao.php');
    if(session_status() === PHP_SESSION_NONE){
        session_start();
    };
    $retorno = [
        'status'   => '',
        'mensagem' => '',
        'data'     => []
    ];

    $id_usuario  = $_SESSION['usuario']['id'];

    if(isset($_GET['id'])){
        $quantidade    = $_POST['quantidade']    ?: null;
        $data_validade = $_POST['data_validade'] ?: null;
        $marca         = $_POST['marca']         ?: null;

        // Garante que o item pertence ao usuário via JOIN
        $stmt = $conexao->prepare("
            UPDATE item_estoque ie
            INNER JOIN produto p ON p.id = ie.id_produto
            SET ie.quantidade    = ?,
                ie.data_validade = ?,
                ie.marca         = ?
            WHERE ie.id = ?
            AND p.id_usuario = ?
        ");
        $stmt->bind_param('dssii', $quantidade, $data_validade, $marca, $_GET['id'], $id_usuario);
        $stmt->execute();

        if($stmt->affected_rows > 0){
            $retorno = [
                'status'   => 'ok',
                'mensagem' => 'Item alterado com sucesso',
                'data'     => []
            ];
        } else {
            $retorno = [
                'status'   => 'nok',
                'mensagem' => 'Nenhuma alteração realizada',
                'data'     => []
            ];
        }
        $stmt->close();
    } else {
        $retorno = [
            'status'   => 'nok',
            'mensagem' => 'ID não informado',
            'data'     => []
        ];
    }

    $conexao->close();
    header("Content-type: application/json; charset=utf-8");
    echo json_encode($retorno);
?>