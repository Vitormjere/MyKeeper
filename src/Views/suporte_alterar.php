<?php
    include_once(__DIR__ . '/../../config/valida_sessao.php');
    include_once(__DIR__ . '/../../config/valida_admin.php');
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Suporte</title>
    <link rel="stylesheet" href="/mykeeper/public/css/suporte_alterar.css">
    <link rel="stylesheet" href="/mykeeper/public/css/notificacao_excluir.css">
</head>

<body>
    <section>
        <a href="/mykeeper/src/Views/suporte.php">
        <img src="/mykeeper/public/assets/perto.png" alt="x.png" style="position:fixed; top:12px; left:12px; width:32px; height:32px; object-fit:contain;">
    </a>
        <form>
            <div>
                <label for="nome">Nome</label>
                <input type="text" name="nome" id="nome">
                <p id="error-nome"></p>
                <input type="hidden" id="id">
            </div>

            <div>
                <label for="email">Email</label>
                <input type="email" name="email" id="email">
                <p id="error-email"></p>
            </div>
            <div>
                <p id="error"></p>
            </div>
            <button type="button" id="alterarsuporte">Alterar</button>
        </form>
    </section>

    <script src="/mykeeper/public/js/notificacao_excluir.js"></script>
    <script src="/mykeeper/public/js/suporte_alterar.js"></script>
</body>

</html>
