<?php
$token = $_GET['token'] ?? '';
if(empty($token)){ 
    die('Link inválido.'); 
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Receita - MyKeeper</title>
    <link rel="stylesheet" href="/mykeeper/public/css/receitas_guest.css">
</head>
<body>
    <div class="logo">MyKeeper</div>
    <div id="conteudo">Carregando...</div>

    <script>
        const token = new URLSearchParams(window.location.search).get('token');
    </script>
    <script src="/mykeeper/public/js/receitas_guest.js"></script>
</body>
</html>