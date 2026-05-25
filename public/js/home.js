document.addEventListener('DOMContentLoaded', async () => {
    // 1. Primeiro verifica se está logado
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