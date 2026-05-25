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
           <a href="/mykeeper/src/Views/home.php">
                <img src="/mykeeper/public/assets/perto.png" alt="x.png" 
                    style="position:fixed; top:12px; left:12px; width:32px; height:32px; object-fit:contain;">
            </a>
            <div>
                <h2>Página de entrada para Administradores</h2>
            </div>
            <div>
                <form>
                    <input type="password" id="senha" placeholder="Senha de acesso">
                    <button type="button" id="entrar">Entrar</button>
                    <p id="error"></p>
                </form>
            </div>
        </div>
    </section>

<script src="/mykeeper/public/js/admin_login.js"></script>
</body>
</html>