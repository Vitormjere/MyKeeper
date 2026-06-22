<?php
    include_once(__DIR__ . '/../../config/valida_sessao.php');
    include_once(__DIR__ . '/../../config/check_permissao_adm.php');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Categoria</title>
    <link rel="stylesheet" href="/mykeeper/public/css/categoria_alterar.css">
</head>
<body>
<section>
    <a href="/mykeeper/categoria">
        <img src="/mykeeper/public/assets/perto.png" alt="x.png" style="position:fixed; top:12px; left:12px; width:32px; height:32px; object-fit:contain;">
    </a>
    <form>
        <div>
            <label for="nome_categoria">Nome: <span style="color: red;">*</span></label>
            <input type="text" name="nome_categoria" id="nome_categoria">
            <p id="error-nome"></p>
            <input type="hidden" id="id">
        </div>
        <div>
            <label for="descricao_categoria">Descrição: <span style="color: red;">*</span></label>
            <input type="text" name="descricao_categoria" id="descricao_categoria">
            <p id="error-descricao"></p>
        </div>
        <div>
            <label for="icone_categoria">Ícone da categoria:</label>
            <input type="file" name="icone_categoria" id="icone_categoria" accept="image/png, image/jpeg, image/jpg">
        </div>
        <div>
            <p id="error"></p>
        </div>
        <div>
            <img src="" id="preview" style="display:none; width:100px; height:100px;">
        </div>
        <button type="button" id="alterarcategoria">Alterar</button>

        <span id="significadoAspas" style= "font-size: 0.72rem; color: #555; text-align: left;">*: Campo obrigatório</span>

    </form>
</section>
<script src="/mykeeper/public/js/categoria_alterar.js"></script>
</body>
</html>