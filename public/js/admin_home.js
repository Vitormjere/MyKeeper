document.addEventListener('DOMContentLoaded', async () => {
    const response = await fetch('/mykeeper/config/check_session.php');
    const data = await response.json();
    if (!data.logado) {
        if (data.expirado) {
            window.location.href = '/mykeeper/usuario_login?motivo=expirado';
        } else {
            window.location.href = '/mykeeper/usuario_login';
        }
        return;
    }
});

document.getElementById('cadastroSuporteButtonLink').addEventListener('click', () => {
    window.location.href = '/mykeeper/suporte';
});

document.getElementById('ticketsSuporte').addEventListener('click', () => {
    window.location.href = '/mykeeper/tickets_suporte';
});