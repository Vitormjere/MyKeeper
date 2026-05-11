<?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    include_once(__DIR__ . '/../../config/headers.php');
    include_once(__DIR__ . '/../../config/conexao.php');

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $retorno = [
        'status'   => '', 
        'mensagem' => '',
        'data'     => []
    ];

    $id_usuario = $_SESSION['usuario']['id'];

    $nome_estoque       = $_POST['nome_estoque'];
    $icone_estoque      = null;

    if (isset($_FILES['icone_estoque']) && $_FILES['icone_estoque']['error'] === 0) {

        $extensoesPermitidas = ['jpg', 'jpeg', 'png'];
        $extensao = strtolower(pathinfo($_FILES['icone_estoque']['name'], PATHINFO_EXTENSION));

        if (!in_array($extensao, $extensoesPermitidas)) {
            echo json_encode([
                'status' => 'nok',
                'mensagem' => 'Formato de imagem inválido'
            ]);
            exit;
        }

        if ($_FILES['icone_estoque']['size'] > 2 * 1024 * 1024) {
            echo json_encode([
                'status' => 'nok',
                'mensagem' => 'Imagem deve ter no máximo 2MB'
            ]);
            exit;
        }

        $nomeArquivo = uniqid('estoque_') . '.' . $extensao;

        $pastaFisica = dirname(__DIR__, 2) . '/public/uploads/estoques/';

        $caminhoURL = '/mykeeper/public/uploads/estoques/' . $nomeArquivo;

        if (move_uploaded_file($_FILES['icone_estoque']['tmp_name'], $pastaFisica . $nomeArquivo)) {
            $icone_estoque = $caminhoURL;
        } else {
            echo json_encode([
                'status' => 'nok',
                'mensagem' => 'Erro ao salvar o ícone'
            ]);
            exit;
        }
    }

    // preparando inserção no banco de dados

    $stmt = $conexao->prepare("INSERT INTO estoque(nome_estoque, icone_estoque, id_usuario) VALUES(?,?,?)");

    $stmt->bind_param("sss", $nome_estoque, $icone_estoque, $id_usuario);
    // inserindo
    $stmt->execute();

    if($stmt->affected_rows > 0){
        $retorno = [
            'status' => 'ok', //ok ou nok
            'mensagem' => 'Estoque inserido com sucesso', //mensagem que envio para o front
            'data' => []
        ];
    }else{
        $retorno = [
            'status' => 'nok', //ok ou nok
            'mensagem' => 'Falha ao inserir o estoque', //mensagem que envio para o front
            'data' => []
        ];
    }

    $stmt->close();
    $conexao->close();

    header("Content-type:application/json;charset:utf-8");
    echo json_encode($retorno);