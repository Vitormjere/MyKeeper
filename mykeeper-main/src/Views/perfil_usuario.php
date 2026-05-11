<?php
    include_once(__DIR__ . '/../../config/valida_sessao.php');
    include_once(__DIR__ . '/sidebar.php'); 
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil do Usuário</title>
    <link rel="stylesheet" href="../../public/css/perfil_usuario.css">
</head>
<body>
      
    <section>
        <div>
            <h2>Perfil</h2>
        </div>
        
        <div>
            <p><strong>Nome:</strong> <span id="nome"></span></p>
            <p><strong>Email:</strong> <span id="email"></span></p>
            <p><strong>CEP:</strong> <span id="cep"></span></p>
        </div>

        <div>
            <button class = "btn-editar"><a href="" id="linkEditar">Editar</a></button>
            <button class="btn-desativar" id="desativarConta">Desativar Conta</button>
        </div>

    </section>

    <script src="../../public/js/perfil_usuario.js"></script>
    <script src="../../public/js/sidebar.js"></script>
</body>
</html>
