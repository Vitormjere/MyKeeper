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

    $stmt = $conexao->prepare("SELECT icone FROM categoria WHERE id = ? AND id_usuario = ?");
    $stmt->bind_param('ii', $_GET['id'], $id_usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $icone = null;

    if($resultado->num_rows > 0){
        $linha = $resultado->fetch_assoc();
        $icone = $linha['icone'];
    }

    $stmt->close();

    if($icone){
        // transforma URL em caminho físico
        if (preg_match('#(?:\.\./\.\./)?(public/uploads/.+)$#', str_replace('\\', '/', $icone), $matches)) {
            $caminhoFisico = dirname(__DIR__, 2) . '/' . $matches[1];
        } else {
            $caminhoFisico = null;
        }

        if($caminhoFisico && file_exists($caminhoFisico)){
            unlink($caminhoFisico);
        }
    }

    $stmt = $conexao->prepare("DELETE FROM categoria WHERE id = ? AND id_usuario = ?");
    $stmt->bind_param('ii', $_GET['id'], $id_usuario);
    $stmt->execute();

    if($stmt->affected_rows > 0){
        $retorno = [
            'status' => 'ok',
            'mensagem' => 'Categoria excluída com sucesso',
            'data' => []
        ];
    }else{
        $retorno = [
            'status' => 'nok',
            'mensagem' => 'Categoria não excluída',
            'data' => []
        ];
    }

    $stmt->close();

}else{
    $retorno = [
        'status' => 'nok',
        'mensagem' => 'É necessário informar um ID para exclusão',
        'data' => []
    ];
}

$conexao->close();

header("Content-type:application/json;charset:utf-8");
echo json_encode($retorno);
