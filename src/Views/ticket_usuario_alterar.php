<?php
    include_once(__DIR__ . '/../../config/valida_sessao.php');  
    include_once(__DIR__ . '/../../config/check_permissao_adm.php');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar ticket</title>
    <link rel="stylesheet" href="/mykeeper/public/css/ticket_usuario_alterar.css">
    <link rel="stylesheet" href="/mykeeper/public/css/notificacao_excluir.css">
</head>
<body>
    <section>
        <a href="/mykeeper/src/Views/ticket_usuario.php">
        <img src="/mykeeper/public/assets/perto.png" alt="x.png" style="position:fixed; top:12px; left:12px; width:32px; height:32px; object-fit:contain;">
    </a>
        <div>
            <h2>Alterar ticket</h2>
        </div>
        <div>
            <p>Preencha os dados abaixo para alterar o ticket</p>
        </div>
        <form>
            <div>
                <label for="titulo">Título: <span style="color: red;">*</span></label>
                <input type="text" name="titulo" id="titulo" placeholder="Título do ticket">
                <p id="error-nome"></p>
                <input type="hidden" id="ticketId">
            </div>
            <div>
                <label for="descricao">Descrição: <span style="color: red;">*</span></label>
                <textarea name="descricao" id="descricao" placeholder="Descrição do ticket" style="resize: none;"></textarea>
                <p id="error-descricao"></p>
            </div>
            <div>
                <p id="error"></p>
            </div>
            <button type="button" id="alterarTicket">Alterar ticket</button>
            <button type="button" id="excluirTicket" style="background-color: #e74c3c; margin-left: 10px;">Excluir ticket</button>
        </form>
    </section>
    <script src="/mykeeper/public/js/notificacao_excluir.js"></script>
    <script src="/mykeeper/public/js/ticket_usuario_alterar.js"></script>
</body>
</html>
