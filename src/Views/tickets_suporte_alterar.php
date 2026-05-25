<?php
    include_once(__DIR__ . '/../../config/valida_sessao.php');  
    include_once(__DIR__ . '/../../config/valida_admin.php');
    include_once(__DIR__ . '/../../config/valida_tipo_usuario.php');

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar ticket</title>
    <link rel="stylesheet" href="/mykeeper/public/css/tickets_suporte_alterar.css">
    <link rel="stylesheet" href="/mykeeper/public/css/notificacao_excluir.css">
</head>
<body>
    <section>
        <a href="/mykeeper/src/Views/tickets_suporte.php">
        <img src="/mykeeper/public/assets/perto.png" alt="x.png" style="position:fixed; top:12px; left:12px; width:32px; height:32px; object-fit:contain;">
    </a>
        <div>
            <h2>Atualizar ticket</h2>
        </div>
        <div>
            <p>Preencha os dados abaixo para atualizar o ticket</p>
        </div>
        <form>
            <div>
                <label for="usuario">Usuário</label>
                <span id="usuario">Nome Usuário</span>
            </div>
            <div>
                <label for="titulo">Título</label>
                <span id="titulo">Título</span>
                <input type="hidden" id="ticketId">
            </div>
            <div>
                <label for="descricao">Descrição</label>
                <span id="descricao">Descrição</span>
            </div>
            <div>
                <label for="data_ticket">Data de Abertura</label>
                <span id="data_ticket">Data de Abertura</span>
            </div>
            <div>
                <label for="resposta_ticket">Resposta</label>
                <textarea name="resposta_ticket" id="resposta_ticket" placeholder="Resposta do ticker" style="resize: none;"></textarea>
                <p id="error-resposta"></p>
            </div>
            <div>
                <label for="status_ticket">Status</label>
                <select name="status_ticket" id="status_ticket">
                    <option value="ticket_aberto">Aberto</option>
                    <option value="ticket_respondido">Respondido</option>
                    <option value="ticket_atualizado">Atualizado</option>
                    <option value="ticket_encerrado">Fechado</option>
                </select>
                <p id="error-status"></p>
            </div>
            <div>
                <p id="error"></p>
            </div>
            <button type="button" id="alterarTicket">Alterar ticket</button>
        </form>
    </section>
    <script src="/mykeeper/public/js/notificacao_excluir.js"></script>
    <script src="/mykeeper/public/js/tickets_suporte_alterar.js"></script>
</body>
</html>
