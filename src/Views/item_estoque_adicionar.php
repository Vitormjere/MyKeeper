<?php
    include_once(__DIR__ . '/../../config/valida_sessao.php');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Item ao Estoque</title>
    <link rel="stylesheet" href="/mykeeper/public/css/item_estoque_adicionar.css">
</head>
<body>
<section>
    <a id="btn-voltar" href="#">
        <img src="/mykeeper/public/assets/perto.png" alt="x.png" style="position:fixed; top:12px; left:12px; width:32px; height:32px; object-fit:contain;">
    </a>
    <div class="header-estoque">
        <h2>Adicionar Item</h2>
    </div>
    <input type="hidden" id="id_estoque">
    <div id="item"></div>
    <p id="mensagem"></p>
</section>
<script src="/mykeeper/public/js/item_estoque_adicionar.js"></script>
</body>
</html>