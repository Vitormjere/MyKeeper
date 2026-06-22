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

$id_usuario   = $_SESSION['usuario']['id'];
$titulo       = trim($_POST['titulo'] ?? '');
$descricao    = trim($_POST['descricao'] ?? '');
$ingredientes = $_POST['ingredientes'] ?? [];

if (!$titulo) {
    echo json_encode(['status' => 'nok', 'mensagem' => 'Título obrigatório.', 'data' => []]);
    exit;
}

if (empty($ingredientes)) {
    echo json_encode(['status' => 'nok', 'mensagem' => 'Adicione ao menos um ingrediente.', 'data' => []]);
    exit;
}

$conexao->begin_transaction();

// 1. insere a receita
$stmt = $conexao->prepare("
    INSERT INTO receita (id_usuario, titulo, descricao, gerada_por_ia)
    VALUES (?, ?, ?, 0)
");
$stmt->bind_param('iss', $id_usuario, $titulo, $descricao);
$stmt->execute();

if ($stmt->affected_rows === 0) {
    $conexao->rollback();
    echo json_encode(['status' => 'nok', 'mensagem' => 'Falha ao salvar receita.', 'data' => []]);
    exit;
}

$id_receita = $conexao->insert_id;
$stmt->close();

// 2. processa cada ingrediente
foreach ($ingredientes as $i => $ing) {
    $nome         = trim($ing['nome'] ?? '');
    $id_categoria = !empty($ing['id_categoria']) ? (int) $ing['id_categoria'] : null;
    $und_medida   = trim($ing['und_medida'] ?? '');
    $qtd          = (float) ($ing['qtd'] ?? 0);

    if (!$nome || !$id_categoria || !$und_medida || $qtd <= 0) continue;

    // verifica se produto já existe para este usuário (ignorando maiúsculas/espaços)
    $stmtCheck = $conexao->prepare("
        SELECT id FROM produto
        WHERE LOWER(TRIM(nome)) = LOWER(TRIM(?)) AND id_usuario = ?
        LIMIT 1
    ");
    $stmtCheck->bind_param('si', $nome, $id_usuario);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();

    if ($resultCheck->num_rows > 0) {
        $id_produto = $resultCheck->fetch_assoc()['id'];
        $stmtCheck->close();
    } else {
        $stmtCheck->close();

        // processa imagem se enviada
        $imagem = null;

        if (isset($_FILES['ingredientes']['error'][$i]['imagem'])
            && $_FILES['ingredientes']['error'][$i]['imagem'] === UPLOAD_ERR_OK) {

            $extensoesPermitidas = ['jpg', 'jpeg', 'png'];
            $ext = strtolower(pathinfo($_FILES['ingredientes']['name'][$i]['imagem'], PATHINFO_EXTENSION));

            if (in_array($ext, $extensoesPermitidas)
                && $_FILES['ingredientes']['size'][$i]['imagem'] <= 2 * 1024 * 1024) {

                $nomeArquivo = uniqid('produto_') . '.' . $ext;
                $pastaFisica = dirname(__DIR__, 2) . '/public/uploads/produtos/';
                $caminhoURL  = '/mykeeper/public/uploads/produtos/' . $nomeArquivo;

                if (move_uploaded_file($_FILES['ingredientes']['tmp_name'][$i]['imagem'], $pastaFisica . $nomeArquivo)) {
                    $imagem = $caminhoURL;
                }
            }
        }

        // cria o produto
        $stmtProd = $conexao->prepare("
            INSERT INTO produto (nome, id_categoria, und_medida, imagem, id_usuario)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmtProd->bind_param('sissi', $nome, $id_categoria, $und_medida, $imagem, $id_usuario);
        $stmtProd->execute();

        if ($stmtProd->affected_rows === 0) {
            $conexao->rollback();
            echo json_encode(['status' => 'nok', 'mensagem' => 'Falha ao salvar produto: ' . $nome, 'data' => []]);
            exit;
        }

        $id_produto = $conexao->insert_id;
        $stmtProd->close();
    }

    // insere o ingrediente na receita
    $stmtIng = $conexao->prepare("
        INSERT INTO item_ingrediente (id_receita, id_produto, qtd, und_medida)
        VALUES (?, ?, ?, ?)
    ");
    $stmtIng->bind_param('iids', $id_receita, $id_produto, $qtd, $und_medida);
    $stmtIng->execute();
    $stmtIng->close();
}

$conexao->commit();
$conexao->close();

$retorno = [
    'status'   => 'ok',
    'mensagem' => 'Receita cadastrada com sucesso.',
    'data'     => []
];

header("Content-type: application/json; charset=utf-8");
echo json_encode($retorno);