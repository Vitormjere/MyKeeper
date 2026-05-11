<?php
    include_once(__DIR__ . '/../../config/valida_sessao.php');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Perfil</title>
    <link rel="stylesheet" href="../../public/css/perfil_alterar.css">
</head>
<body>
    <section>
        <form>
            <div>
                <label for="nome">Nome</label>
                <input type="text" name="nome" id="nome">
            </div>

            <div>
                <label for="email">Email</label>
                <input type="email" name="email" id="email">
            </div>

            <div>
                <label for="cep">CEP</label>
                <input type="text" name="cep" id="cep" placeholder="00000-000" maxlength="9" inputmode="numeric">
            </div>

            <button type="button" id="alterarperfil">Salvar</button>
        </form>
    </section>

    <script src="../../public/js/perfil_alterar.js?v=20260406-cep"></script>
</body>
</html>
