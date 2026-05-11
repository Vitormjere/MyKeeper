<?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
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

        $nome = $_POST['nome_produto'];
        $id_categoria = !empty($_POST['id_categoria']) ? (int)$_POST['id_categoria'] : null;
        $und_medida = $_POST['und_medida_produto'];

        $imagem = null;

        if(isset($_FILES['icone_produto']) && $_FILES['icone_produto']['error'] == 0){

            $ext = pathinfo($_FILES['icone_produto']['name'], PATHINFO_EXTENSION);
            $nome_arquivo = uniqid('produto_') . '.' . $ext;

            $pastaFisica = dirname(__DIR__, 2) . '/public/uploads/produtos/';
            $caminhoURL = '../../public/uploads/produtos/' . $nome_arquivo;

            if(move_uploaded_file($_FILES['icone_produto']['tmp_name'], $pastaFisica . $nome_arquivo)){
                $imagem = $caminhoURL;
            } else {
                echo json_encode([
                    'status' => 'nok',
                    'mensagem' => 'Erro ao mover imagem'
                ]);
                exit;
            }
        }

        if($imagem){
            $stmt = $conexao->prepare("
                UPDATE produto 
                SET nome=?, id_categoria=?, und_medida=?, imagem=?
                WHERE id=? AND id_usuario = ?
            ");
            $stmt->bind_param("siisii", $nome, $id_categoria, $und_medida, $imagem, $_GET['id'], $id_usuario);
        } else {
            $stmt = $conexao->prepare("
                UPDATE produto 
                SET nome=?, id_categoria=?, und_medida=?
                WHERE id=? AND id_usuario = ?
            ");
            $stmt->bind_param("sisis", $nome, $id_categoria, $und_medida, $_GET['id'], $id_usuario);
        }

        if(!$stmt->execute()){
            echo json_encode([
                'status' => 'nok',
                'mensagem' => $stmt->error
            ]);
            exit;
        }

        $retorno = [
            'status' => 'ok',
            'mensagem' => 'Produto alterado com sucesso',
            'data' => []
        ];

        $stmt->close();

    }else{
        $retorno = [
            'status' => 'nok',
            'mensagem' => 'ID não informado',
            'data' => []
        ];
    }

    $conexao->close();

    header("Content-type: application/json; charset=utf-8");
    echo json_encode($retorno);
