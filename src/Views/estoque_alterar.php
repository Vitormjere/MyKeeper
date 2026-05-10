<?php
    include_once(__DIR__ . '/../../config/valida_sessao.php');
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Estoque</title>
    <link rel="stylesheet" href="/mykeeper/public/css/estoque_novo.css">
</head>

<body>
    <section>
        <a href="/mykeeper/src/Views/estoque.php">
            <img src="/mykeeper/public/assets/perto.png" alt="x.png" style="position:fixed; top:12px; left:12px; width:32px; height:32px; object-fit:contain;">
        </a>
        <form>
            <div>
                <label for="nome_estoque">Nome</label>
                <input type="text" name="nome_estoque" id="nome_estoque">
                <input type="hidden" name="id" id="id">
                <p id="error-nome"></p>
            </div>

            <div>
                <label for="icone_estoque">Ícone do estoque</label>
                <input type="file" name="icone_estoque" id="icone_estoque" accept="image/png, image/jpeg, image/jpg">
            </div>

            <div>
                <img src="" id="preview" style="display:none; width:100px; height:100px;">
            </div>
            <div>
                <p id="error"></p>
            </div>
            <button type="button" id="alterarestoque">Alterar</button>
        </form>
    </section>

    <script src="/mykeeper/public/js/estoque_alterar.js"></script>
</body>

</html>
