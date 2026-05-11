<?php
    session_start();
    include_once('verifica_login_realizado.php');
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../../public/css/usuario_login.css">
</head>

<body>
    <section>
        <div>
            <div>
                <h2>Bem-vindo ao MyKeeper</h2>
            </div>
            <div>
                <p>Entre com sua conta para acessar seu estoque alimenticio</p>
            </div>

            <form id="formLogin">

                <div>
                    <div>
                        <label for="email">E-mail</label>
                    </div>
                    <div>
                        <input type="text" name="email" id="email" placeholder="seu@email.com">
                    </div>
                </div>


                <div>
                    <div>
                        <label for="senha">Senha</label> <br>
                    </div>
                    <div>
                        <input type="password" name="senha" id="senha" placeholder="••••••" minLength="8">
                    </div>
                    <div>
                        <p>Esqueceu a senha? <a href="#">Redefinir</a></p>
                    </div>
                </div>

                <button type="submit">Entrar</button>
            </form>

            <div class="divider">
                <span>AINDA NÃO TEM CONTA?</span>
                <button type="button" id="createAccount">Criar uma conta</button>
            </div>

        </div>
        
    </section>

<script src="../../public/js/login.js"></script>
</body>
</html>
