<link rel="stylesheet" href="/mykeeper/public/css/sidebar.css">

<aside class="sideNavBar">
    <div>
        <h2>MyKeeper</h2>
    </div>
    <div>
        <nav>
            <button id="inicioButtonLink">Início</button>

            <?php if (isset($_SESSION['usuario']) && $_SESSION['usuario']['tipo'] === 0): ?>
                <button id="estoquesButtonLink">Estoques</button>
                <button id="comprasButtonLink">Compras</button>
                <button id="produtosButtonLink">Produtos registrados</button>
                <button id="categoriasButtonLink">Categorias</button>
                <button id="receitasButtonLink">Receitas</button>
                <button id="ticketButtonLink">Tickets</button>
            <?php endif; ?>

            <button id="perfilButtonLink">Perfil</button>

            <?php if(isset($_SESSION['usuario']) && $_SESSION['usuario']['tipo'] === 1): ?>
                <button id="suporteButtonLink">Suporte</button>
                <button id="TicketsButtonLinkSuporte">Tickets</button>
            <?php endif; ?>

            <button id="logoffButtonLink">Sair</button>

            <?php if (isset($_SESSION['usuario']['nome'])): ?>
                <span class="usuarioLogado"><?= htmlspecialchars($_SESSION['usuario']['nome']) ?></span>
            <?php endif; ?>

        </nav>
    </div>
</aside>