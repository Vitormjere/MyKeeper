<?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    include_once(__DIR__ . '/../../config/headers.php');
    include_once(__DIR__ . '/../../config/conexao.php');
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    if (empty($_SESSION['usuario']['id'])) {
        echo json_encode(['status' => 'nok', 'mensagem' => 'Usuário não autenticado']);
        exit;
    }

    $id_ticket      = intval($_POST['id_ticket']      ?? 0);
    $mensagem       = trim($_POST['mensagem']          ?? '');
    $tipo_remetente = trim($_POST['tipo_remetente']    ?? '');
    $id_remetente   = $_SESSION['usuario']['id'];
    $tipo_sessao    = $_SESSION['usuario']['tipo'];

    if ($id_ticket === 0 || $mensagem === '' || !in_array($tipo_remetente, ['usuario', 'suporte'])) {
        echo json_encode(['status' => 'nok', 'mensagem' => 'Dados inválidos']);
        exit;
    }

    // Verifica se o ticket existe e se o status permite envio
    $stmt = $conexao->prepare("SELECT id, status_ticket, id_usuario FROM ticket_suporte WHERE id = ?");
    $stmt->bind_param('i', $id_ticket);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 0) {
        echo json_encode(['status' => 'nok', 'mensagem' => 'Ticket não encontrado']);
        exit;
    }

    $ticket = $resultado->fetch_assoc();
    $stmt->close();

    // Usuário comum só pode acessar os próprios tickets
    if ($tipo_sessao === 0 && $ticket['id_usuario'] != $id_remetente) {
        echo json_encode(['status' => 'nok', 'mensagem' => 'Acesso negado']);
        exit;
    }

    $statusPermitido = ['ticket_aberto', 'ticket_atualizado', 'ticket_respondido'];
    if (!in_array($ticket['status_ticket'], $statusPermitido)) {
        echo json_encode(['status' => 'nok', 'mensagem' => 'Este ticket não permite novas mensagens']);
        exit;
    }

    // Determina novo status após envio
    // Usuário responde → ticket_atualizado | Suporte responde → ticket_respondido
    $novoStatus = ($tipo_remetente === 'suporte') ? 'ticket_respondido' : 'ticket_atualizado';

    try {
        // Insere a mensagem
        $stmt = $conexao->prepare(
            "INSERT INTO ticket_mensagem (id_ticket, id_remetente, tipo_remetente, mensagem)
            VALUES (?, ?, ?, ?)"
        );
        $stmt->bind_param('iiss', $id_ticket, $id_remetente, $tipo_remetente, $mensagem);
        $stmt->execute();
        $id_msg = $stmt->insert_id;
        $stmt->close();

        // Atualiza o status do ticket
        $stmt = $conexao->prepare("UPDATE ticket_suporte SET status_ticket = ? WHERE id = ?");
        $stmt->bind_param('si', $novoStatus, $id_ticket);
        $stmt->execute();
        $stmt->close();

        // Busca a mensagem recém-inserida com data_envio e nome
        $tabela_nome = ($tipo_remetente === 'suporte') ? 'suporte' : 'usuario';
        $stmt = $conexao->prepare(
            "SELECT tm.id, tm.id_remetente, tm.tipo_remetente, tm.mensagem, tm.data_envio,
                    r.nome AS nome_remetente
            FROM ticket_mensagem tm
            JOIN $tabela_nome r ON r.id = tm.id_remetente
            WHERE tm.id = ?"
        );
        $stmt->bind_param('i', $id_msg);
        $stmt->execute();
        $msg = $stmt->get_result()->fetch_assoc();
        $msg['status_ticket'] = $novoStatus;
        $stmt->close();

        $retorno = ['status' => 'ok', 'data' => $msg];

    } catch (mysqli_sql_exception $e) {
        $retorno = ['status' => 'nok', 'mensagem' => 'Erro ao enviar mensagem: ' . $e->getMessage()];
    }

    $conexao->close();

header('Content-Type: application/json; charset=utf-8');
echo json_encode($retorno);