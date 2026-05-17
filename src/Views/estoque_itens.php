<?php
    include_once(__DIR__ . '/../../config/valida_sessao.php');
    include_once(__DIR__ . '/sidebar.php');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Itens do Estoque</title>
    <link rel="stylesheet" href="/mykeeper/public/css/estoque_itens.css">
    <link rel="stylesheet" href="/mykeeper/public/css/notificacao_excluir.css">
</head>
<body>
    <section>
        
        <div class="header-estoque">
            <a href="/mykeeper/src/Views/estoque.php">
                <img src="/mykeeper/public/assets/perto.png" alt="x.png" style="width:32px; height:32px; object-fit:contain;">
            </a>
            <h2>Itens do Estoque</h2>
            <button id="adicionarItem">Novo Item</button>
        </div>
        <div id="item"></div>
        <p id="mensagem"></p>
    </section>
    
    <script src="/mykeeper/public/js/notificacao_excluir.js"></script>
    <script src="/mykeeper/public/js/estoque_itens.js"></script>
    <script src="/mykeeper/public/js/sidebar.js"></script>
</body>
</html>