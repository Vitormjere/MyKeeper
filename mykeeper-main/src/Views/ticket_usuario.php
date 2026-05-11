<?php
    include_once(__DIR__ . '/../../config/valida_sessao.php');  
    include_once(__DIR__ . '/sidebar.php');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tickets de Suporte</title>
    <link rel="stylesheet" href="../../public/css/ticket_usuario.css">
</head>
<body>    
    <section>
        <div>
            <h2>Tickets de Suporte</h2>
        </div>
        <div id="item"></div>
        <div>
            <button type="button" id="ticket_novo" class="addvs">Adicionar Ticket</button>
        </div>
    </section>
    <script src="../../public/js/ticket.js"></script>
    <script src="../../public/js/sidebar.js"></script>
</body>
</html>
