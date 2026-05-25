<?php
    include_once(__DIR__ . '/../../config/valida_sessao.php');
    include_once(__DIR__ . '/sidebar.php');
    include_once(__DIR__ . '/../../config/check_permissao_adm.php');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Concluir Lista</title>
    <link rel="stylesheet" href="/mykeeper/public/css/compras_concluir.css">
    <link rel="stylesheet" href="/mykeeper/public/css/notificacao_excluir.css">
</head>
<body>

    <section>
        <div class="header-estoque">
            <h2>Concluir Lista de Compras</h2>
        </div>

        <div class="select-estoque-wrapper">
            <label for="selectEstoque">Selecione o estoque de destino: <span style="color: red;">*</span></label>
            <select id="selectEstoque">
                <option value="">Carregando estoques...</option>
            </select>
        </div>

        <div id="item"></div>
        <p id="mensagem"></p>

        <button id="btnConfirmar">Confirmar e Concluir</button>

        <span id="significadoAspas" style= "font-size: 0.72rem; color: #555; text-align: left;">*: Campo obrigatório</span>

    </section>
    
    <script src="/mykeeper/public/js/notificacao_excluir.js"></script>
    <script src="/mykeeper/public/js/compras_concluir.js"></script>
    <script src="/mykeeper/public/js/sidebar.js"></script>

</body>
</html>
