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
    <link rel="stylesheet" href="../../public/css/suporte_alterar.css">
</head>

<body>
    <section>
        <form>
            <div>
                <label for="nome">Nome</label>
                <input type="text" name="nome" id="nome">
                <input type="hidden" id="id">
            </div>

            <div>
                <label for="email">Email</label>
                <input type="email" name="email" id="email">
            </div>
            
            <button type="button" id="alterarsuporte">Alterar</button>
        </form>
    </section>

    <script src="../../public/js/suporte_alterar.js"></script>
</body>

</html>
