<?php
    include_once(__DIR__ . '/../../config/valida_sessao.php');  
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo ticket</title>
    <link rel="stylesheet" href="../../public/css/ticket_usuario_novo.css">
</head>

<body>
    <section>
            <div>
                <h2>Criar ticket</h2>
            </div>
            <div>
                <p>Preencha os dados abaixo para criar um novo ticket</p>
            </div>

            <form>
                <div>
                    <div>
                        <label for="titulo">Título</label>
                    </div>
                    <div>
                        <input type="text" name="titulo" id="titulo" placeholder="Título do ticket">
                    </div>
                </div>
                <div>
                    <div>
                        <label for="descricao">Descrição</label>
                    </div>
                    <div>
                        <input type="text" name="descricao" id="descricao" placeholder="Descrição do ticket">
                    </div>
                </div>

                <button id="criarTicket">Criar ticket</button>
            </form>
    </section>
</body>
<script src="../../public/js/ticket_usuario_novo.js"></script>
</html>
