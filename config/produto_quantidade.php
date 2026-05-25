<?php
function garantir_coluna_quantidade_produto($conexao) {
    $resultado = $conexao->query("SHOW COLUMNS FROM produto LIKE 'quantidade'");

    if ($resultado && $resultado->num_rows > 0) {
        return;
    }

    if (!$conexao->query("ALTER TABLE produto ADD COLUMN quantidade DOUBLE NOT NULL DEFAULT 0 AFTER nome")) {
        echo json_encode([
            'status' => 'nok',
            'mensagem' => 'Falha ao preparar o campo de quantidade do produto',
            'data' => []
        ]);
        exit;
    }
}
