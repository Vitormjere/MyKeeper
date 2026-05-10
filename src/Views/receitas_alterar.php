<?php
include_once(__DIR__ . '/../../config/valida_sessao.php');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Receita</title>
    <link rel="stylesheet" href="/mykeeper/public/css/receitas_alterar.css">
</head>
<body>
    <section>
        <a href="/mykeeper/src/Views/receitas.php">
            <img src="/mykeeper/public/assets/perto.png" alt="x.png" style="position:fixed; top:12px; left:12px; width:32px; height:32px; object-fit:contain;">
        </a>
        <form>
            <div>
                <label for="titulo">Título</label>
                <input type="text" name="titulo" id="titulo">
                <p id="error-titulo"></p>
                <input type="hidden" id="id">
            </div>

            <div>
                <label for="descricao">Descrição</label>
                <textarea name="descricao" id="descricao"></textarea>
                <p id="error-descricao"></p>
            </div>

            <div>
                <label>Ingredientes</label>

                <!-- modelo clonado pelo JS, não visível -->
                <div class="ingrediente" id="modelo-ingrediente" style="display:none;">
                    <select class="select-produto">
                        <option value="" data-placeholder="true">Escolha o produto</option>
                    </select>
                    <input type="number" class="input-qtd" min="0.01" step="0.01">
                    <input type="text" class="input-und" placeholder="Unidade (g, ml, xícara...)">
                    <button type="button" class="btn-remover">Remover</button>
                </div>

                <div id="lista-ingredientes"></div>
                <p id="error-ingredientes"></p>

                <button type="button" id="adicionar-ingrediente">+ Ingrediente</button>
            </div>

            <div>
                <p id="error"></p>
            </div>
            <button type="button" id="alterarreceita">Salvar</button>
        </form>
    </section>
    <script src="/mykeeper/public/js/receitas_alterar.js"></script>
</body>
</html>