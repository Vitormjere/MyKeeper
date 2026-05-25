<?php
    include_once(__DIR__ . '/../../config/valida_sessao.php');
    include_once(__DIR__ . '/../../config/check_permissao_adm.php');
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Lista de Compras</title>
    <link rel="stylesheet" href="/mykeeper/public/css/compras_novo.css">
</head>

<body>
    <section>
        <a href="/mykeeper/src/Views/compras.php">
            <img src="/mykeeper/public/assets/perto.png" alt="x.png" style="position:fixed; top:12px; left:12px; width:32px; height:32px; object-fit:contain;">
        </a>
        <form>
            <div>
                <label for="titulo">Titulo</label>
                <input type="text" name="titulo" id="titulo">
                <p id="error-nome"></p>
            </div>
                <p id="error"></p>
            </div>
            <button type="button" id="addcompras">Adicionar</button>
        </form>
    </section>

    <script src="/mykeeper/public/js/compras_novo.js"></script>
</body>

</html>
