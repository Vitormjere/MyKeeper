<?php
    include_once(__DIR__ . '/../../config/headers.php');
    include_once(__DIR__ . '/../../config/conexao.php');

    if(session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $retorno = [
        'status' => '', //ok ou nok
        'mensagem' => '', //mensagem que envio para o front
        'data' => []
    ];

    $id_usuario = $_SESSION['usuario']['id'];

    if(isset($_GET['id'])){

        $stmt = $conexao->prepare("SELECT icone_estoque FROM estoque WHERE id = ? AND id_usuario = ?");
        $stmt->bind_param('ss', $_GET['id'], $id_usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();

        $imagem = null;

        if($resultado->num_rows > 0){
            $linha = $resultado->fetch_assoc();
            $imagem = $linha['icone_estoque'];
        }

        $stmt->close();

        if($imagem){
            // transforma URL em caminho físico
            $caminhoFisico = dirname(__DIR__, 2) . str_replace('/mykeeper', '', $imagem);

            if(file_exists($caminhoFisico)){
                unlink($caminhoFisico);
            }
        }

        $stmt = $conexao->prepare("DELETE FROM estoque WHERE id = ? AND id_usuario = ?");
        $stmt->bind_param('ss', $_GET['id'], $id_usuario);
        $stmt->execute();

        if($stmt->affected_rows > 0){
            $retorno = [
                'status' => 'ok', //ok ou nok
                'mensagem' => 'Estoque excluido', //mensagem que envio para o front
                'data' => []
            ];
        }else{
            $retorno = [
                'status' => 'nok', //ok ou nok
                'mensagem' => 'Estoque não excluido', //mensagem que envio para o front
                'data' => []
            ];
        }

        $stmt->close();
    }else{
        $retorno = [
            'status' => 'nok', //ok ou nok
            'mensagem' => 'É necessário informar um ID para exclusão', //mensagem que envio para o front
            'data' => []
        ];
    };

    $conexao->close();

    header("Content-type:application/json;charset:utf-8");
    echo json_encode($retorno);