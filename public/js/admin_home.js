document.addEventListener('DOMContentLoaded', async () => {
    const response = await fetch('/mykeeper/config/check_session.php');
    const data = await response.json();
    if (!data.logado) {
        if (data.expirado) {
            window.location.href = '/mykeeper/src/Views/usuario_login.php?motivo=expirado';
        } else {
            window.location.href = '/mykeeper/src/Views/usuario_login.php';
        }
        return;
    }
});

document.getElementById('cadastroSuporteButtonLink').addEventListener('click', () => {
    window.location.href = '/mykeeper/src/Views/suporte.php';
});

document.getElementById('ticketsSuporte').addEventListener('click', () => {
    window.location.href = '/mykeeper/src/Views/tickets_suporte.php';
});