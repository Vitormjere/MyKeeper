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
$nome_categoria      = $_POST['nome_categoria'];
$descricao_categoria = $_POST['descricao_categoria'];
$icone_categoria     = null;

if (isset($_FILES['icone_categoria']) && $_FILES['icone_categoria']['error'] === 0) {

    $extensoesPermitidas = ['jpg', 'jpeg', 'png'];
    $extensao = strtolower(pathinfo($_FILES['icone_categoria']['name'], PATHINFO_EXTENSION));

    if (!in_array($extensao, $extensoesPermitidas)) {
        echo json_encode([
            'status' => 'nok',
            'mensagem' => 'Formato de imagem inválido'
        ]);
        exit;
    }

    if ($_FILES['icone_categoria']['size'] > 2 * 1024 * 1024) {
        echo json_encode([
            'status' => 'nok',
            'mensagem' => 'Imagem deve ter no máximo 2MB'
        ]);
        exit;
    }

    $nomeArquivo = uniqid('categoria_') . '.' . $extensao;

    $pastaFisica = dirname(__DIR__, 2) . '/public/uploads/categorias/';

    $caminhoURL = '../../public/uploads/categorias/' . $nomeArquivo;

    if (move_uploaded_file($_FILES['icone_categoria']['tmp_name'], $pastaFisica . $nomeArquivo)) {
        $icone_categoria = $caminhoURL;
    } else {
        echo json_encode([
            'status' => 'nok',
            'mensagem' => 'Erro ao salvar o ícone'
        ]);
        exit;
    }
}

$stmt = $conexao->prepare("INSERT INTO categoria (nome, descricao, icone, id_usuario) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $nome_categoria, $descricao_categoria, $icone_categoria, $id_usuario);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    $retorno['status']   = 'ok';
    $retorno['mensagem'] = 'Categoria inserida com sucesso';
} else {
    $retorno['status']   = 'nok';
    $retorno['mensagem'] = 'Falha ao inserir a categoria';
}

$stmt->close();
$conexao->close();

header("Content-type:application/json;charset:utf-8");
echo json_encode($retorno);
