<?php
    include_once(__DIR__ . '/../../config/valida_sessao.php');
    include_once(__DIR__ . '/../../config/check_permissao_adm.php');
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Lista de Compras</title>
    <link rel="stylesheet" href="/mykeeper/public/css/compras_novo.css">
</head>

<body>
    <section>
        <a href="/mykeeper/src/Views/compras.php">
            <img src="/mykeeper/public/assets/perto.png" alt="x.png" style="position:fixed; top:12px; left:12px; width:32px; height:32px; object-fit:contain;">
        </a>
        <form>
            <div>
                <label for="titulo">Titulo: <span style="color: red;">*</span></label>
                <input type="text" name="titulo" id="titulo">
                <input type="hidden" name="id" id="id">
                <p id="error-titulo"></p>
            </div>
                <p id="error"></p>
            </div>
            <button type="button" id="addcompras">Alterar</button>

            <span id="significadoAspas" style= "font-size: 0.72rem; color: #555; text-align: left;">*: Campo obrigatório</span>
        </form>
    </section>

    <script src="/mykeeper/public/js/compras_alterar.js"></script>
</body>

</html>
