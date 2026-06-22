<?php
include_once(__DIR__ . '/../../config/valida_sessao.php');
include_once(__DIR__ . '/../../config/check_permissao_adm.php');
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
        <a href="/mykeeper/receitas">
            <img src="/mykeeper/public/assets/perto.png" alt="voltar" style="position:fixed; top:12px; left:12px; width:32px; height:32px; object-fit:contain;">
        </a>
        <form>
            <input type="hidden" id="id">

            <div>
                <label for="titulo">Título: <span style="color: red;">*</span></label>
                <input type="text" name="titulo" id="titulo">
                <p id="error-titulo"></p>
            </div>

            <div>
                <label for="descricao">Descrição: </label>
                <textarea name="descricao" id="descricao" style="resize:none;"></textarea>
                <p id="error-descricao"></p>
            </div>

            <div id="container-ingredientes">
                <label>Ingredientes: <span style="color: red;">*</span></label>

                <!-- modelo clonado pelo JS -->
                <div class="ingrediente" id="modelo-ingrediente" style="display:none;">
                    <div class="ingrediente-header">
                        <span class="ingrediente-titulo">Ingrediente</span>
                        <button type="button" class="btn-remover">Remover</button>
                    </div>
                    <div class="ingrediente-campos">
                        <div class="campo-grupo">
                            <label>Nome do produto: <span style="color: red;">*</span></label>
                            <input type="text" class="input-nome" placeholder="Ex: Farinha de trigo">
                        </div>
                        <div class="campo-grupo">
                            <label>Categoria: <span style="color: red;">*</span></label>
                            <select class="select-categoria">
                                <option value="" data-placeholder="true">Escolha a categoria</option>
                            </select>
                        </div>
                        <div class="campo-grupo">
                            <label>Unidade de medida: <span style="color: red;">*</span></label>
                            <select class="select-und-medida">
                                <option value="" data-placeholder="true">Escolha a unidade</option>
                                <option value="kg">Quilo (kg)</option>
                                <option value="gramas">Gramas (g)</option>
                                <option value="l">Litro (L)</option>
                                <option value="ml">Mililitro (mL)</option>
                                <option value="unidade">Unidade</option>
                                <option value="xicara">Xícara</option>
                                <option value="colher_sopa">Colher de sopa</option>
                                <option value="colher_cha">Colher de chá</option>
                            </select>
                        </div>
                        <div class="campo-grupo campo-grupo--row">
                            <div>
                                <label>Quantidade: <span style="color: red;">*</span></label>
                                <input type="number" class="input-qtd" placeholder="0" min="0.01" step="0.01">
                            </div>
                            <div>
                                <label>Ícone (opcional)</label>
                                <input type="file" class="input-imagem" accept="image/png, image/jpeg, image/jpg">
                            </div>
                        </div>
                    </div>
                </div>

                <div id="lista-ingredientes"></div>
                <p id="error-ingredientes"></p>
                <button type="button" id="adicionar-ingrediente">+ Adicionar ingrediente</button>
                <span id="significadoAspas" style= "font-size: 0.72rem; color: #555; text-align: left;">*: Campo obrigatório</span>
            </div>

            <div>
                <p id="error"></p>
            </div>
            <button type="button" id="alterarreceita">Salvar</button>
            <span id="significadoAspas" style= "font-size: 0.72rem; color: #555; text-align: left;">*: Campo obrigatório</span>
        </form>
    </section>
    <script src="/mykeeper/public/js/receitas_alterar.js"></script>
</body>
</html>