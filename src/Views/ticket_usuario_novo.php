<?php
    include_once(__DIR__ . '/../../config/valida_sessao.php');  
    include_once(__DIR__ . '/../../config/check_permissao_adm.php');
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo ticket</title>
    <link rel="stylesheet" href="/mykeeper/public/css/ticket_usuario_novo.css">
</head>

<body>
    <section>
        <a href="/mykeeper/ticket_usuario">
        <img src="/mykeeper/public/assets/perto.png" alt="x.png" style="position:fixed; top:12px; left:12px; width:32px; height:32px; object-fit:contain;">
    </a>
            <div>
                <h2>Criar ticket</h2>
            </div>
            <div>
                <p>Preencha os dados abaixo para criar um novo ticket</p>
            </div>

            <form>
                <div>
                    <div>
                        <label for="titulo">Título: <span style="color: red;">*</span></label>
                    </div>
                    <div>
                        <input type="text" name="titulo" id="titulo" placeholder="Título do ticket">
                        <p id="error-nome"></p>
                    </div>
                </div>
                <div>
                    <div>
                        <label for="descricao">Descrição: <span style="color: red;">*</span></label>
                    </div>
                    <div>
                        <textarea name="descricao" id="descricao" style="resize: none;" placeholder="Descrição do ticket"></textarea>
                        <p id="error-descricao"></p>
                    </div>
                </div>
                <div>
                    <p id="error"></p>
                </div>
                <button type="button" id="criarTicket">Criar ticket</button>
                <span id="significadoAspas" style= "font-size: 0.72rem; color: #555; text-align: left;">*: Campo obrigatório</span>
            </form>
    </section>
</body>
<script src="/mykeeper/public/js/ticket_usuario_novo.js"></script>
</html>