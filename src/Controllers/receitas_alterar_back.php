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

    $id_usuario   = $_SESSION['usuario']['id'];
    $id_receita   = isset($_GET['id']) ? (int) $_GET['id'] : 0;
    $titulo       = trim($_POST['titulo'] ?? '');
    $descricao    = trim($_POST['descricao'] ?? '');
    $ingredientes = $_POST['ingredientes'] ?? [];

    if (!$id_receita) {
        echo json_encode(['status' => 'nok', 'mensagem' => 'ID não informado.', 'data' => []]);
        exit;
    }

    if (!$titulo) {
        echo json_encode(['status' => 'nok', 'mensagem' => 'Título obrigatório.', 'data' => []]);
        exit;
    }

    if (empty($ingredientes)) {
        echo json_encode(['status' => 'nok', 'mensagem' => 'Adicione ao menos um ingrediente.', 'data' => []]);
        exit;
    }

    // confirma que a receita pertence ao usuário
    $stmtOwn = $conexao->prepare("SELECT id FROM receita WHERE id = ? AND id_usuario = ?");
    $stmtOwn->bind_param('ii', $id_receita, $id_usuario);
    $stmtOwn->execute();

    if ($stmtOwn->get_result()->num_rows === 0) {
        echo json_encode(['status' => 'nok', 'mensagem' => 'Receita não encontrada.', 'data' => []]);
        exit;
    }

    $stmtOwn->close();
    $conexao->begin_transaction();

    // 1. atualiza a receita
    $stmt = $conexao->prepare("UPDATE receita SET titulo = ?, descricao = ? WHERE id = ?");
    $stmt->bind_param('ssi', $titulo, $descricao, $id_receita);
    $stmt->execute();
    $affectedRows = $stmt->affected_rows; // captura o número de linhas afetadas    
    $stmt->close();

    // 2. remove ingredientes antigos
    $stmtDel = $conexao->prepare("DELETE FROM item_ingrediente WHERE id_receita = ?");
    $stmtDel->bind_param('i', $id_receita);
    $stmtDel->execute();
    $stmtDel->close();

    // 3. processa ingredientes (mesma lógica do novo)
    foreach ($ingredientes as $i => $ing) {
        $nome         = trim($ing['nome'] ?? '');
        $id_categoria = !empty($ing['id_categoria']) ? (int) $ing['id_categoria'] : null;
        $und_medida   = trim($ing['und_medida'] ?? '');
        $qtd          = (float) ($ing['qtd'] ?? 0);

        if (!$nome || !$id_categoria || !$und_medida || $qtd <= 0) continue;

        $stmtCheck = $conexao->prepare("
            SELECT id FROM produto
            WHERE nome = ? AND id_categoria = ? AND und_medida = ? AND id_usuario = ?
            LIMIT 1
        ");
        $stmtCheck->bind_param('sisi', $nome, $id_categoria, $und_medida, $id_usuario);
        $stmtCheck->execute();
        $resultCheck = $stmtCheck->get_result();

        if ($resultCheck->num_rows > 0) {
            $id_produto = $resultCheck->fetch_assoc()['id'];
            $stmtCheck->close();
        } else {
            $stmtCheck->close();

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

    if ($affectedRows > 0) {
        $retorno = [
            'status'   => 'ok',
            'mensagem' => 'Receita alterada com sucesso',
            'data'     => []
        ];
    } else {
        $retorno = [
            'status'   => 'nok',
            'mensagem' => 'Nenhuma alteração realizada',
            'data'     => []
        ];
    }

header("Content-type: application/json; charset=utf-8");
echo json_encode($retorno);