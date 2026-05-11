<?php
    include_once(__DIR__ . '/../../config/valida_sessao.php');
    include_once(__DIR__ . '/../../config/valida_admin.php');
    include_once(__DIR__ . '/sidebar.php');
?>

<!DOCTYPE html>
<html lang="pt-br">    

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Home</title>
    <link rel="stylesheet" href="../../public/css/admin_home.css">
</head>

<body>
    <div>
        <h2>Admin Home</h2>
    </div>
    
    <section>
        <div>
            <button id="cadastroSuporteButtonLink">Cadastrar Suporte</button>
            <button id="ticketsSuporte">Tickets de Suporte</button>
        </div>
    </section>
    
</body>

<script src="../../public/js/admin_home.js"></script>
<script src="../../public/js/sidebar.js"></script>
</html>
