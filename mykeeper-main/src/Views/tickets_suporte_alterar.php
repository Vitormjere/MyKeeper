<?php
    include_once(__DIR__ . '/../../config/valida_sessao.php');  
    include_once(__DIR__ . '/../../config/valida_admin.php');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar ticket</title>
    <link rel="stylesheet" href="../../public/css/tickets_suporte_alterar.css">
</head>
<body>
    <section>
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
                <input type="text" name="resposta_ticket" id="resposta_ticket" placeholder="Resposta do ticket">
            </div>
            <div>
                <label for="status_ticket">Status</label>
                <select name="status_ticket" id="status_ticket">
                    <option value="ticket_aberto">Aberto</option>
                    <option value="ticket_respondido">Respondido</option>
                    <option value="ticket_atualizado">Atualizado</option>
                    <option value="ticket_encerrado">Fechado</option>
                </select>
            </div>
            <button type="button" id="alterarTicket">Alterar ticket</button>
        </form>
    </section>
    <script src="../../public/js/tickets_suporte_alterar.js"></script>
</body>
</html>
