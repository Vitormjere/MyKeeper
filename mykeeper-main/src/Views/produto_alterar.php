<?php
include_once(__DIR__ . '/../../config/valida_sessao.php');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Produto</title>
    <link rel="stylesheet" href="../../public/css/produto_alterar.css">
</head>
<body>
    <section>
        <form>
            <div>
                <label for="nome_produto">Nome</label>
                <input type="text" name="nome_produto" id="nome_produto">
                <input type="hidden" id="id">
            </div>

            <div>
                <label for="categoria_produto">Categoria</label>
                <select name="categoria_produto" id="categoria_produto">
                    <option value="" data-placeholder="true">Escolha a categoria do produto</option>
                </select>
            </div>
            
            <div>
                <label for="und_medida_produto">Unidade de medida</label>
                <input type="text" name="und_medida_produto" id="und_medida_produto">
            </div>

            <div>
                <label for="icone_produto">Ícone do produto</label>
                <input type="file" name="icone_produto" id="icone_produto" accept="image/png, image/jpeg, image/jpg">
            </div>

            <div>
                <img src="" id="preview" style="display:none; width:100px; height:100px;">
            </div>

            <button type="button" id="alterarproduto">Salvar</button>
        </form>
    </section>

    <script src="../../public/js/produto_alterar.js"></script>
</body>
</html>
