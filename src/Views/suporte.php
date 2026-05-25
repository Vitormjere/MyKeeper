<?php
    include_once(__DIR__ . '/../../config/valida_sessao.php');
    include_once(__DIR__ . '/../../config/valida_admin.php');
    include_once(__DIR__ . '/../../config/valida_tipo_usuario.php');
    include_once(__DIR__ . '/sidebar.php');
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contas de Suporte</title>
    <link rel="stylesheet" href="/mykeeper/public/css/suporte.css">
    <link rel="stylesheet" href="/mykeeper/public/css/notificacao_excluir.css">
</head>
<body>    
    <section>
        <div>
            <h2>Contas de Suporte</h2>
        </div>
        <div id="item"></div>
        <div><p id="error"></p></div>
        <div>
            <button type="button" id="suporte_novo" class="addvs">Adicionar Suporte</button>
        </div>

    </section>
    <script src="/mykeeper/public/js/notificacao_excluir.js"></script>
    <script src="/mykeeper/public/js/suporte.js"></script>
    <script src="/mykeeper/public/js/sidebar.js"></script>
</body>
</html>