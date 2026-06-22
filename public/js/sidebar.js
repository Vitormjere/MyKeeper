function addClick(id, url) {
    const el = document.getElementById(id);
    if (el) el.addEventListener('click', () => window.location.href = url);
}

addClick('inicioButtonLink',    '/mykeeper/home');
addClick('estoquesButtonLink',  '/mykeeper/estoque');
addClick('comprasButtonLink',   '/mykeeper/compras');
addClick('produtosButtonLink',  '/mykeeper/produto');
addClick('categoriasButtonLink','/mykeeper/categoria');
addClick('receitasButtonLink',  '/mykeeper/receitas');
addClick('perfilButtonLink',    '/mykeeper/perfil_usuario');
addClick('ticketButtonLink',    '/mykeeper/ticket_usuario');
addClick('logoffButtonLink',    '/mykeeper/src/Controllers/logoff.php');
addClick('TicketsButtonLinkSuporte', '/mykeeper/tickets_suporte');
addClick('suporteButtonLink', '/mykeeper/suporte');