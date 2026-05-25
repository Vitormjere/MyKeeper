<?php
    include_once(__DIR__ . '/../../config/valida_sessao.php');
    include_once(__DIR__ . '/../../config/check_permissao_adm.php');
    include_once(__DIR__ . '/sidebar.php');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Itens da Lista de Compras</title>
    <link rel="stylesheet" href="/mykeeper/public/css/compras_itens.css">
    <link rel="stylesheet" href="/mykeeper/public/css/notificacao_excluir.css">
</head>
<body>
    <section>
        
        <div class="header-lista">
            <a href="/mykeeper/src/Views/compras.php">
                <img src="/mykeeper/public/assets/perto.png" alt="x.png" style="width:32px; height:32px; object-fit:contain;">
            </a>
            <h2>Itens da Lista de Compras</h2>
            <button id="adicionarItem">Novo Item</button>
        </div>
        <div id="item"></div>
        <p id="mensagem"></p>
    </section>
    
    <script src="/mykeeper/public/js/notificacao_excluir.js"></script>
    <script src="/mykeeper/public/js/compras_itens.js"></script>
    <script src="/mykeeper/public/js/sidebar.js"></script>
</body>
</html>