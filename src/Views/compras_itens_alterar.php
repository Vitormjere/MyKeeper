<?php
    include_once(__DIR__ . '/../../config/valida_sessao.php');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Item da Lista</title>
    <link rel="stylesheet" href="/mykeeper/public/css/compras_itens_alterar.css">
</head>
<body>
<section>
    <a id="btn-voltar" href="#">
        <img src="/mykeeper/public/assets/perto.png" alt="x.png" style="position:fixed; top:12px; left:12px; width:32px; height:32px; object-fit:contain;">
    </a>
    <form>
        <div>
            <h2 id="nome-produto"></h2>
        </div>
        <input type="hidden" id="id_produto">
        <input type="hidden" id="id_lista_compra">
        <div>
            <label for="quantidade">Quantidade</label>
            <input type="number" id="quantidade" placeholder="Quantidade" min="0" step="0.01">
            <p id="error-quantidade"></p>
        </div>
        <div>
            <p id="error"></p>
        </div>
        <button type="button" id="alteraritem">Salvar</button>
    </form>
</section>
<script src="/mykeeper/public/js/compras_itens_alterar.js"></script>
</body>
</html>