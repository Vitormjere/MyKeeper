<link rel="stylesheet" href="/mykeeper/public/css/sidebar.css">

<aside class="sideNavBar">
    <div>
        <h2>MyKeeper</h2>
    </div>
    <div>
        <nav>
            <button id="inicioButtonLink">Início</button>
            <button id="estoquesButtonLink">Estoques</button>
            <button id="comprasButtonLink">Compras</button>
            <button id="produtosButtonLink">Produtos registrados</button>
            <button id="categoriasButtonLink">Categorias</button>
            <!-- <button id="avencerButtonLink">A Vencer</button> -->
            <!-- <button id="historicoButtonLink">Historico</button>  -->
            <button id="receitasButtonLink">Receitas</button>
            <button id="perfilButtonLink">Perfil</button>
            <?php if(isset($_SESSION['usuario']) && $_SESSION['usuario']['tipo'] === 1): ?>
                <button id="adminHomeButtonLink">Admin</button>
            <?php endif; ?>
            <button id="ticketButtonLink">Tickets</button>
            <button id="logoffButtonLink">Sair</button>
        </nav>
    </div>
</aside>
