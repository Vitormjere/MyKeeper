<?php
    include_once(__DIR__ . '/../../config/valida_sessao.php');
    include_once(__DIR__ . '/sidebar.php'); 
?>
<!DOCTYPE html>
<html lang="pt-br">    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - MyKeeper</title>
    <link rel="stylesheet" href="../../public/css/home.css">
</head>
<body>
    <section class="main-content">
        <div class="welcome-container">
            <h1>Bem-vindo ao <span>MyKeeper</span></h1>
            <p>Gerencie seu inventário e organize seus itens de forma inteligente.</p>
        </div>

        <div class="stats-grid">
            <a href="produto.php" class="card-link">
                <div class="home-card">
                    <div class="icon">📦</div>
                    <h3>Meus Produtos</h3>
                    <p>Visualize e gerencie todos os itens registrados.</p>
                    <span class="btn-atv">Acessar</span>
                </div>
            </a>

            <a href="categoria.php" class="card-link">
                <div class="home-card">
                    <div class="icon">🏷️</div>
                    <h3>Categorias</h3>
                    <p>Organize seus produtos por grupos e tipos.</p>
                    <span class="btn-atv">Ver Mais</span>
                </div>
            </a>

            <a href="ticket_usuario.php" class="card-link">
                <div class="home-card">
                    <div class="icon">🎧</div>
                    <h3>Suporte</h3>
                    <p>Precisa de ajuda? Abra um ticket ou veja suas respostas.</p>
                    <span class="btn-atv">Abrir Ticket</span>
                </div>
            </a>
        </div>
    </section>

    <script src="../../public/js/home.js"></script>
    <script src="../../public/js/sidebar.js"></script>
</body>
</html>
