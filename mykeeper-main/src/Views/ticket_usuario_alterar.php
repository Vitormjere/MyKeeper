<?php
    include_once(__DIR__ . '/../../config/valida_sessao.php');  
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar ticket</title>
    <link rel="stylesheet" href="../../public/css/ticket_usuario_alterar.css">
</head>
<body>
    <section>
        <div>
            <h2>Alterar ticket</h2>
        </div>
        <div>
            <p>Preencha os dados abaixo para alterar o ticket</p>
        </div>
        <form>
            <div>
                <label for="titulo">Título</label>
                <input type="text" name="titulo" id="titulo" placeholder="Título do ticket">
                <input type="hidden" id="ticketId">
            </div>
            <div>
                <label for="descricao">Descrição</label>
                <input type="text" name="descricao" id="descricao" placeholder="Descrição do ticket">
            </div>
            <button type="button" id="alterarTicket">Alterar ticket</button>
        </form>
    </section>
    <script src="../../public/js/ticket_usuario_alterar.js"></script>
</body>
</html>
