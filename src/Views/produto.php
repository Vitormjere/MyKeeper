<?php
include_once(__DIR__ . '/../../config/valida_sessao.php');
include_once(__DIR__ . '/sidebar.php');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos</title>
    <link rel="stylesheet" href="/mykeeper/public/css/produto.css">
    <link rel="stylesheet" href="/mykeeper/public/css/notificacao_excluir.css">
</head>
<body>  
    <section>
        <div>
            <h2>Produtos Registrados</h2>
        </div>
        <div id="item"></div>
        <p id="mensagem"></p>
        <div>
            <button type="button" id="produto_novo" class="addvs">Adicionar Produto</button>
        </div>
    </section>
    <script src="/mykeeper/public/js/notificacao_excluir.js"></script>
    <script src="/mykeeper/public/js/produto.js"></script>
    <script src="/mykeeper/public/js/sidebar.js"></script>
</body>
</html>