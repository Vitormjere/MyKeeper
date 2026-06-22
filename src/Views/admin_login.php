<?php
    include_once(__DIR__ . '/../../config/valida_sessao.php');
    if ($_SESSION['usuario']['tipo'] == 0){
        include_once(__DIR__ . '/../../config/valida_tipo_usuario.php');
    } 
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN</title>
    <link rel="stylesheet" href="/mykeeper/public/css/admin_login.css">
</head>

<body>
    <section>
        <div>
           <a href="/mykeeper/home">
                <img src="/mykeeper/public/assets/perto.png" alt="x.png" 
                    style="position:fixed; top:12px; left:12px; width:32px; height:32px; object-fit:contain;">
            </a>
            <div>
                <h2>Acesso para Administradores</h2>
            </div>
            <div>
                <form>
                    <label for="senha">Senha de acesso: <span style="color: red;">*</span></label>
                    <input required type="password" id="senha" placeholder="Insira a senha de acesso">
                    <p id="error"></p>
                    <button type="button" id="entrar">Entrar</button>
                    
                    <span id="significadoAspas">*: Campo obrigatório</span>
                </form>
            </div>
        </div>
    </section>

<script src="/mykeeper/public/js/admin_login.js"></script>
</body>
</html>