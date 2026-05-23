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
    <link rel="stylesheet" href="/mykeeper/public/css/home.css">
</head>
<body>
    <?php if (isset($_SESSION['usuario']) && $_SESSION['usuario']['tipo'] === 1): 
        echo '<script>window.location.href = "/mykeeper/src/Views/admin_home.php";</script>';
        exit();    
    ?>
    <?php endif; ?>

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

            <a href="estoque.php" class="card-link">
                <div class="home-card">
                    <div class="icon">&#x1F4E5;</div>
                    <h3>Estoque</h3>
                    <p>Gerencie o estoque de seus produtos.</p>
                    <span class="btn-atv">Ver Mais</span>
                </div>
            </a>

            <a href="compras.php" class="card-link">
                <div class="home-card">
                    <div class="icon">&#x1F4DD;</div>
                    <h3>Lista de Compras</h3>
                    <p>Gerencia suas listas de compras.</p>
                    <span class="btn-atv">Ver Mais</span>
                </div>
            </a>

            <a href="receitas.php" class="card-link">
                <div class="home-card">
                    <div class="icon">&#x1F35A;</div>
                    <h3>Receitas</h3>
                    <p>Gerencie suas receitas e ingredientes.</p>
                    <span class="btn-atv">Ver Mais</span>
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

    <script src="/mykeeper/public/js/home.js"></script>
    <script src="/mykeeper/public/js/sidebar.js"></script>
</body>
</html>