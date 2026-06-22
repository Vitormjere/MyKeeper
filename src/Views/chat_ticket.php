<?php
    include_once(__DIR__ . '/../../config/valida_sessao.php');

    $id_ticket = intval($_GET['id'] ?? 0);
    if ($id_ticket === 0) {
        header('Location: /mykeeper/home');
        exit;
    }

    $tipo = $_SESSION['usuario']['tipo'];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat - Ticket #<?= $id_ticket ?></title>
    <link rel="stylesheet" href="/mykeeper/public/css/chat_ticket.css">
</head>
<body>

    <div class="chat-wrapper">

        <div class="chat-header">
            <button class="btn-voltar" id="btn-voltar" title="Voltar"><img src="/mykeeper/public/assets/perto.png" alt="x.png" style="width:32px; height:32px; object-fit:contain;"></button>

            <div class="chat-header-info">
                <h2 id="ticket-titulo">Carregando...</h2>
                <div class="ticket-meta">Ticket #<?= $id_ticket ?></div>
            </div>

            <span class="status-badge" id="ticket-status-badge"></span>
        </div>

        <div class="chat-messages" id="chat-messages">
            <div class="chat-vazio" id="chat-vazio">
                <p>Nenhuma mensagem ainda.</p>
            </div>
        </div>

        <div class="chat-input-area">

            <div class="chat-input-row">
                <textarea
                    id="input-mensagem"
                    placeholder="Digite sua mensagem..."
                    rows="1"
                    maxlength="2000"
                ></textarea>

                <button id="btn-enviar" title="Enviar">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="22" y1="2" x2="11" y2="13"></line>
                        <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                    </svg>
                </button>

            </div>

            <p id="error-chat"></p>
            <p class="chat-bloqueado-msg" id="chat-bloqueado-msg" style="display:none;">
                Este ticket está encerrado. Não é possível enviar novas mensagens.
            </p>
            
        </div>

    </div>

    <script>
        const ID_TICKET    = <?= $id_ticket ?>;
        const TIPO_SESSAO  = <?= $tipo ?>; // 0 = usuario, 1 = suporte
        const ID_USUARIO   = <?= (int) $_SESSION['usuario']['id'] ?>;
    </script>
    <script src="/mykeeper/public/js/chat_ticket.js"></script>
</body>
</html>