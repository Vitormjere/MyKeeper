document.getElementById('inicioButtonLink').addEventListener('click', () => {
    window.location.href = '/mykeeper/src/Views/home.php';
});

document.getElementById('produtosButtonLink').addEventListener('click', () => {
    window.location.href = '/mykeeper/src/Views/produto.php';
});   

document.getElementById('categoriasButtonLink').addEventListener('click', () => {
    window.location.href = '/mykeeper/src/Views/categoria.php';
});

document.getElementById('logoffButtonLink').addEventListener('click', () => {
    window.location.href = '/mykeeper/src/Controllers/logoff.php';
});

document.getElementById('perfilButtonLink').addEventListener('click', () => {
    window.location.href = '/mykeeper/src/Views/perfil_usuario.php';
});

document.getElementById('ticketButtonLink').addEventListener('click', () => {
    window.location.href = '/mykeeper/src/Views/ticket_usuario.php';
});

document.getElementById('receitasButtonLink').addEventListener('click', () => {
    window.location.href = '/mykeeper/src/Views/receitas.php';
})

document.getElementById('estoquesButtonLink').addEventListener('click', () => {
    window.location.href = '/mykeeper/src/Views/estoque.php';
});

document.getElementById('comprasButtonLink').addEventListener('click', () => {
    window.location.href = '/mykeeper/src/Views/compras.php';
});

document.getElementById('adminHomeButtonLink').addEventListener('click', () => {
    window.location.href = '/mykeeper/src/Views/admin_home.php';
});