<?php
    include_once(__DIR__ . '/../../config/valida_sessao.php');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Senha</title>
    <link rel="stylesheet" href="/mykeeper/public/css/perfil_alterar.css">
</head>
<body>
    <section>
    <a href="/mykeeper/src/Views/perfil_usuario.php">
        <img src="/mykeeper/public/assets/perto.png" alt="x.png" style="position:fixed; top:12px; left:12px; width:32px; height:32px; object-fit:contain;">
    </a>
        <form>
            <div>
                <label for="senha">Senha: <span style="color: red;">*</span></label>
                <input type="password" name="senha" id="senha">
                <p id="error-senha"></p>
            </div>

            <div>
                <label for="nova_senha">Nova Senha: <span style="color: red;">*</span></label>
                <input type="password" name="nova_senha" id="nova_senha">
                <p id="error-nova_senha"></p>
            </div>

            <div>
                <p id="error"></p>
            </div>
            <button type="button" id="alterarsenha">Salvar</button>
            <span id="significadoAspas" style= "font-size: 0.72rem; color: #555; text-align: left;">*: Campo obrigatório</span>
        </form>
    </section>

    <script src="/mykeeper/public/js/senha_alterar.js"></script>
</body>
</html>