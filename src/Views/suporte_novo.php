<?php
    include_once(__DIR__ . '/../../config/valida_sessao.php');
    include_once(__DIR__ . '/../../config/valida_admin.php');
    include_once(__DIR__ . '/../../config/valida_tipo_usuario.php');

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Suporte</title>
    <link rel="stylesheet" href="/mykeeper/public/css/suporte_novo.css">
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
            </div>

            <div>
                <label for="email">Email</label>
                <input type="email" name="email" id="email">
                <p id="error-email"></p>
            </div>

            <div>
                <label for="cep">CEP</label>
                <input type="text" name="cep" id="cep" placeholder="00000-000" maxlength="9" inputmode="numeric">
                <p id="error-cep"></p>
            </div>

            <div>
                <label for="senha">Senha</label>
                <input type="password" name="senha" id="senha" minlength="8">
                <p id="error-senha"></p>
            </div>
            <div>
                <p id="error"></p>
            </div>
            <button type="button" id="addsuporte">Adicionar</button>
        </form>
    </section>

    <script src="/mykeeper/public/js/suporte_novo.js"></script>
</body>

</html>