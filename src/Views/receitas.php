<?php
include_once(__DIR__ . '/../../config/valida_sessao.php');
include_once(__DIR__ . '/sidebar.php');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receitas</title>
    <link rel="stylesheet" href="/mykeeper/public/css/receitas.css">
</head>
<body>  
    <section>
        <div>
            <h2>Receitas</h2>
        </div>
        <div id="item"></div>
        <p id="mensagem"></p>
        <div>
            <button type="button" id="receita_nova" class="addvs">Adicionar Receita</button>
        </div>
    </section>
    <script src="/mykeeper/public/js/receitas.js"></script>
    <script src="/mykeeper/public/js/sidebar.js"></script>
</body>
</html>