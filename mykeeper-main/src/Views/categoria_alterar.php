<?php
    include_once(__DIR__ . '/../../config/valida_sessao.php');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Categoria</title>
    <link rel="stylesheet" href="../../public/css/categoria_alterar.css">
</head>
<body>
<section>
    <form>
        <div>
            <label for="nome_categoria">Nome</label>
            <input type="text" name="nome_categoria" id="nome_categoria">
            <input type="hidden" id="id">
        </div>
        <div>
            <label for="descricao_categoria">Descrição</label>
            <input type="text" name="descricao_categoria" id="descricao_categoria">
        </div>
        <div>
            <label for="icone_categoria">Ícone da categoria</label>
            <input type="file" name="icone_categoria" id="icone_categoria" accept="image/png, image/jpeg, image/jpg">
        </div>
        <div>
            <img src="" id="preview" style="display:none; width:100px; height:100px;">
        </div>
        <button type="button" id="alterarcategoria">Alterar</button>
    </form>
</section>
<script src="../../public/js/categoria_alterar.js"></script>
</body>
</html>
