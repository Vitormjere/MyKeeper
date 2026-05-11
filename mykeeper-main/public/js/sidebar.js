document.getElementById('inicioButtonLink').addEventListener('click', () => {
    window.location.href = 'home.php';
});

document.getElementById('produtosButtonLink').addEventListener('click', () => {
    window.location.href = 'produto.php';
});   

document.getElementById('categoriasButtonLink').addEventListener('click', () => {
    window.location.href = 'categoria.php';
});

document.getElementById('logoffButtonLink').addEventListener('click', () => {
    window.location.href = '../Controllers/logoff.php';
});

document.getElementById('perfilButtonLink').addEventListener('click', () => {
    window.location.href = 'perfil_usuario.php';
});

document.getElementById('adminHomeButtonLink').addEventListener('click', () => {
    window.location.href = 'admin_home.php';
});

document.getElementById('ticketButtonLink').addEventListener('click', () => {
    window.location.href = 'ticket_usuario.php';
});
