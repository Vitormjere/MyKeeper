addEventListener('DOMContentLoaded', async () => {
    const response = await fetch('../../config/check_session.php');
    const data = await response.json();
    if (!data.logado) {
        window.location.href = 'usuario_login.php';
        return;
    }
});

document.getElementById('cadastroSuporteButtonLink').addEventListener('click', () => {
    window.location.href = 'suporte.php';
});

document.getElementById('ticketsSuporte').addEventListener('click', () => {
    window.location.href = 'tickets_suporte.php';
});
