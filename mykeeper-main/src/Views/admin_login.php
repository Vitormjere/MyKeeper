<?php
    session_start();
    include_once('verifica_login_realizado.php');
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN</title>
    <link rel="stylesheet" href="../../public/css/admin_login.css">
</head>

<body>
    
    <section>
        <div>
            <div>
                <h2>Página de entrada para Administradores</h2>
            </div>
            <div>
                <form>
                    <input type="password" id="senha" placeholder="Senha de acesso">
                    <button type="button" id="entrar">Entrar</button>
                </form>
            </div>
        </div>
    </section>

<script src="../../public/js/admin_login.js"></script>
</body>
</html>
