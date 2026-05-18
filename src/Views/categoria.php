<?php
include_once(__DIR__ . '/../../config/valida_sessao.php');
include_once(__DIR__ . '/sidebar.php'); 
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorias de Produto</title>
    <link rel="stylesheet" href="/mykeeper/public/css/categoria.css">
    <link rel="stylesheet" href="/mykeeper/public/css/notificacao_excluir.css">
</head>
<body>
<section>
    <div>
        <h2>Categorias de Produto</h2>
    </div>
    <div id="item"></div>
    <div><h4 id="mensagem"></h4></div>
    <div style="display: flex; justify-content: flex-end;">
        <button type="button" id="categoria_nova" class="addvs">Adicionar Categoria</button>
    </div>
</section>
<script src="/mykeeper/public/js/notificacao_excluir.js"></script>
<script src="/mykeeper/public/js/categoria.js"></script>
<script src="/mykeeper/public/js/sidebar.js"></script>
</body>
</html>