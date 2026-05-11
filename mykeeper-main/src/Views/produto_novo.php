<?php
include_once(__DIR__ . '/../../config/valida_sessao.php');
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Produto</title>
    <link rel="stylesheet" href="../../public/css/produto_novo.css">
</head>

<body>
    <section>
        <form>
            <div>
                <label for="nome_produto">Nome</label>
                <input type="text" name="nome_produto" id="nome_produto">
            </div>

            <div>
                <label for="categoria_produto">Categoria</label>
                <select name="categoria_produto" id="categoria_produto">
                    <option value="" data-placeholder="true">Escolha a categoria do produto</option>
                </select>
            </div>
            
            <div>
                <label for="und_medida_produto">Unidade de medida</label>
                <select name="und_medida_produto" id="und_medida_produto">
                    <option value="" data-placeholder="true">Escolha como esse item sera medido</option>
                    <option value="kg">Quilo (kg)</option>
                    <option value="gramas">Gramas (g)</option>
                    <option value="l">Litro (l)</option>
                    <option value="ml">Mililitro (ml)</option>
                </select>
            </div>

            <div>
                <label for="icone_produto">Ícone do produto</label>
                <input type="file" name="icone_produto" id="icone_produto" accept="image/png, image/jpeg, image/jpg">
            </div>

            <div>
                <img src="" id="preview" style="display:none; width:100px; height:100px;">
            </div>

            <button type="button" id="addproduto">Adicionar</button>
        </form>
    </section>

    <script src="../../public/js/produto_novo.js"></script>
</body>

</html>
