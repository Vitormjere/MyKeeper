<?php
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

    $nome_produto       = $_POST['nome_produto'];
    $id_categoria       = !empty($_POST['id_categoria']) ? $_POST['id_categoria'] : null; 
    $und_medida_produto = $_POST['und_medida_produto'];
    $icone_produto      = null;

    if (isset($_FILES['icone_produto']) && $_FILES['icone_produto']['error'] === 0) {

        $extensoesPermitidas = ['jpg', 'jpeg', 'png'];
        $extensao = strtolower(pathinfo($_FILES['icone_produto']['name'], PATHINFO_EXTENSION));

        if (!in_array($extensao, $extensoesPermitidas)) {
            echo json_encode([
                'status' => 'nok',
                'mensagem' => 'Formato de imagem inválido'
            ]);
            exit;
        }

        if ($_FILES['icone_produto']['size'] > 2 * 1024 * 1024) {
            echo json_encode([
                'status' => 'nok',
                'mensagem' => 'Imagem deve ter no máximo 2MB'
            ]);
            exit;
        }

        $nomeArquivo = uniqid('produto_') . '.' . $extensao;

        $pastaFisica = dirname(__DIR__, 2) . '/public/uploads/produtos/';

        $caminhoURL = '../../public/uploads/produtos/' . $nomeArquivo;

        if (move_uploaded_file($_FILES['icone_produto']['tmp_name'], $pastaFisica . $nomeArquivo)) {
            $icone_produto = $caminhoURL;
        } else {
            echo json_encode([
                'status' => 'nok',
                'mensagem' => 'Erro ao salvar o ícone'
            ]);
            exit;
        }
    }

    // preparando inserção no banco de dados

    $stmt = $conexao->prepare("INSERT INTO produto(nome, id_categoria, und_medida, imagem, id_usuario) VALUES(?,?,?,?,?)");

    $stmt->bind_param("sssss", $nome_produto, $id_categoria, $und_medida_produto, $icone_produto, $id_usuario);
    // inserindo
    $stmt->execute();

    if($stmt->affected_rows > 0){
        $retorno = [
            'status' => 'ok', //ok ou nok
            'mensagem' => 'Produto inserido com sucesso', //mensagem que envio para o front
            'data' => []
        ];
    }else{
        $retorno = [
            'status' => 'nok', //ok ou nok
            'mensagem' => 'Falha ao inserir o produto', //mensagem que envio para o front
            'data' => []
        ];
    }

    $stmt->close();
    $conexao->close();

    header("Content-type:application/json;charset:utf-8");
    echo json_encode($retorno);
