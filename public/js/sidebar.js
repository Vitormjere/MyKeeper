function addClick(id, url) {
    const el = document.getElementById(id);
    if (el) el.addEventListener('click', () => window.location.href = url);
}

addClick('inicioButtonLink',    '/mykeeper/src/Views/home.php');
addClick('estoquesButtonLink',  '/mykeeper/src/Views/estoque.php');
addClick('comprasButtonLink',   '/mykeeper/src/Views/compras.php');
addClick('produtosButtonLink',  '/mykeeper/src/Views/produto.php');
addClick('categoriasButtonLink','/mykeeper/src/Views/categoria.php');
addClick('receitasButtonLink',  '/mykeeper/src/Views/receitas.php');
addClick('perfilButtonLink',    '/mykeeper/src/Views/perfil_usuario.php');
addClick('ticketButtonLink',    '/mykeeper/src/Views/ticket_usuario.php');
addClick('logoffButtonLink',    '/mykeeper/src/Controllers/logoff.php');
addClick('TicketsButtonLinkSuporte', '/mykeeper/src/Views/tickets_suporte.php');
addClick('suporteButtonLink', '/mykeeper/src/Views/suporte.php');