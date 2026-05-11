<?php
    include_once(__DIR__ . '/../../config/valida_sessao.php');
    include_once(__DIR__ . '/../../config/valida_admin.php');
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Suporte</title>
    <link rel="stylesheet" href="../../public/css/suporte_novo.css">
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
                <label for="senha">Senha</label>
                <input type="password" name="senha" id="senha" minlength="8">
            </div>
            
            <button type="button" id="addsuporte">Adicionar</button>
        </form>
    </section>

    <script src="../../public/js/suporte_novo.js"></script>
</body>

</html>
