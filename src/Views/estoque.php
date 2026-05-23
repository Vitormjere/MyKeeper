<?php
    include_once(__DIR__ . '/../../config/valida_sessao.php');
    include_once(__DIR__ . '/sidebar.php'); 
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estoque</title>
    <link rel="stylesheet" href="/mykeeper/public/css/estoque.css">
    <link rel="stylesheet" href="/mykeeper/public/css/notificacao_excluir.css">
</head>
<body>
    <section>
        <div class="header-estoque">
            <h2>Estoque</h2>
            <button id="criarNovoEstoque">Novo Estoque</button>
        </div>
        
        <div id="item"></div>
        <p id="mensagem"></p>
    </section>

    <script src="/mykeeper/public/js/notificacao_excluir.js"></script>
    <script src="/mykeeper/public/js/estoque.js"></script>
    <script src="/mykeeper/public/js/sidebar.js"></script>
</body>
</html>