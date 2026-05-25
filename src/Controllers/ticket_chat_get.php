<?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    include_once(__DIR__ . '/../../config/headers.php');
    include_once(__DIR__ . '/../../config/conexao.php');

    if (empty($_SESSION['usuario']['id'])) {
        echo json_encode(['status' => 'nok', 'mensagem' => 'Usuário não autenticado']);
        exit;
    }

    $id_ticket = intval($_GET['id_ticket'] ?? 0);
    $id        = $_SESSION['usuario']['id'];
    $tipo      = $_SESSION['usuario']['tipo'];

    if ($id_ticket === 0) {
        echo json_encode(['status' => 'nok', 'mensagem' => 'ID do ticket inválido']);
        exit;
    }

    // Busca o ticket — suporte vê todos, usuário só vê os seus
    if ($tipo === 1) {
        $stmt = $conexao->prepare("SELECT id, titulo, status_ticket FROM ticket_suporte WHERE id = ?");
        $stmt->bind_param('i', $id_ticket);
    } else {
        $stmt = $conexao->prepare("SELECT id, titulo, status_ticket FROM ticket_suporte WHERE id = ? AND id_usuario = ?");
        $stmt->bind_param('ii', $id_ticket, $id);
    }

    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 0) {
        echo json_encode(['status' => 'nok', 'mensagem' => 'Ticket não encontrado']);
        exit;
    }

    $ticket = $resultado->fetch_assoc();
    $stmt->close();

    // Busca as mensagens com o nome do remetente via UNION
    $sql = "
        SELECT
            tm.id,
            tm.id_remetente,
            tm.tipo_remetente,
            tm.mensagem,
            tm.data_envio,
            CASE
                WHEN tm.tipo_remetente = 'usuario' THEN u.nome
                WHEN tm.tipo_remetente = 'suporte' THEN s.nome
            END AS nome_remetente
        FROM ticket_mensagem tm
        LEFT JOIN usuario  u ON tm.tipo_remetente = 'usuario' AND tm.id_remetente = u.id
        LEFT JOIN suporte  s ON tm.tipo_remetente = 'suporte' AND tm.id_remetente = s.id
        WHERE tm.id_ticket = ?
        ORDER BY tm.data_envio ASC
    ";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param('i', $id_ticket);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $mensagens = [];
    while ($row = $resultado->fetch_assoc()) {
        $mensagens[] = $row;
    }
    $stmt->close();
    $conexao->close();

header('Content-Type: application/json; charset=utf-8');
echo json_encode([
    'status' => 'ok',
    'data'   => [
        'ticket'    => $ticket,
        'mensagens' => $mensagens,
    ]
]);