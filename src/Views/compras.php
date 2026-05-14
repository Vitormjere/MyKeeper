<?php
    include_once(__DIR__ . '/../../config/valida_sessao.php');
    include_once(__DIR__ . '/sidebar.php'); 
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listas de Compras</title>
    <link rel="stylesheet" href="/mykeeper/public/css/compras.css">
    
</head>
<body>

    <section>

        <div class="header-estoque">
            <h2>Listas de compras</h2>
            <button id="criarCompras">+ Nova lista</button>
        </div>
        <div id="item"></div>
        <p id="mensagem"></p>
        
    </section>
        
    <script src="/mykeeper/public/js/compras.js"></script>
    <script src="/mykeeper/public/js/sidebar.js"></script>
</body>
</html>