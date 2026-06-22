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

if (isset($_GET['id'])) {

    $id_receita = (int) $_GET['id'];

    $stmt = $conexao->prepare("
        SELECT id, titulo, descricao, gerada_por_ia, data_geracao
        FROM receita
        WHERE id = ? AND id_usuario = ?
    ");
    $stmt->bind_param('ii', $id_receita, $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $retorno = [
            'status'   => 'nok',
            'mensagem' => 'Receita não encontrada.',
            'data'     => []
        ];
    } else {
        $receita = $result->fetch_assoc();
        $receita['gerada_por_ia'] = (bool) $receita['gerada_por_ia'];
        $stmt->close();

        $stmt2 = $conexao->prepare("
            SELECT 
                p.id AS id_produto,
                p.nome,
                p.id_categoria,
                p.und_medida,
                p.imagem,
                ii.qtd,
                ii.und_medida AS und_medida_receita,
                CASE 
                    WHEN SUM(
                        ie.quantidade *
                        CASE LOWER(TRIM(p.und_medida))
                            WHEN 'kg' THEN 1000
                            WHEN 'l'  THEN 1000
                            WHEN 'litro' THEN 1000
                            ELSE 1
                        END
                    ) >= (
                        ii.qtd *
                        CASE LOWER(TRIM(ii.und_medida))
                            WHEN 'kg' THEN 1000
                            WHEN 'l'  THEN 1000
                            WHEN 'litro' THEN 1000
                            ELSE 1
                        END
                    ) THEN 'disponivel'
                    WHEN SUM(ie.quantidade) > 0 THEN 'parcial'
                    ELSE 'indisponivel'
                END AS status_estoque
            FROM item_ingrediente ii
            INNER JOIN produto p ON p.id = ii.id_produto
            LEFT JOIN item_estoque ie ON ie.id_produto = p.id
            LEFT JOIN estoque e ON e.id = ie.id_estoque AND e.id_usuario = ?
            WHERE ii.id_receita = ?
            GROUP BY p.id, p.nome, p.id_categoria, p.und_medida, p.imagem, ii.qtd, ii.und_medida
        ");
        $stmt2->bind_param('ii', $id_usuario, $id_receita);
        $stmt2->execute();
        $result2 = $stmt2->get_result();

        $ingredientes = [];
        while ($row = $result2->fetch_assoc()) {
            $ingredientes[] = $row;
        }

        $stmt2->close();
        $receita['ingredientes'] = $ingredientes;

        $retorno = [
            'status'   => 'ok',
            'mensagem' => 'Receita encontrada.',
            'data'     => $receita
        ];
    }

} else {

    $stmt = $conexao->prepare("
        SELECT id, titulo, descricao, gerada_por_ia, data_geracao
        FROM receita
        WHERE id_usuario = ?
        ORDER BY data_geracao DESC
    ");
    $stmt->bind_param('i', $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    $receitas = [];
    while ($row = $result->fetch_assoc()) {
        $row['gerada_por_ia'] = (bool) $row['gerada_por_ia'];
        $receitas[] = $row;
    }

    $stmt->close();

    $retorno = [
        'status'   => 'ok',
        'mensagem' => 'Receitas encontradas.',
        'data'     => $receitas
    ];
}

$conexao->close();

header("Content-type: application/json; charset=utf-8");
echo json_encode($retorno);