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
            // transforma URL em caminho físico
            if (preg_match('#(?:\.\./\.\./)?(public/uploads/.+)$#', str_replace('\\', '/', $imagem), $matches)) {
                $caminhoFisico = dirname(__DIR__, 2) . '/' . $matches[1];
            } else {
                $caminhoFisico = null;
            }

            if($caminhoFisico && file_exists($caminhoFisico)){
                unlink($caminhoFisico);
            }
        }

        $stmt = $conexao->prepare("DELETE FROM produto WHERE id = ? AND id_usuario = ?");
        $stmt->bind_param('ii', $_GET['id'], $id_usuario);
        $stmt->execute();

        if($stmt->affected_rows > 0){
            $retorno = [
                'status' => 'ok', //ok ou nok
                'mensagem' => 'Produto excluido', //mensagem que envio para o front
                'data' => []
            ];
        }else{
            $retorno = [
                'status' => 'nok', //ok ou nok
                'mensagem' => 'Produto não excluido', //mensagem que envio para o front
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
