document.addEventListener('DOMContentLoaded', async () => {
    // 1. Verifica sessão
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

    document.getElementById('entrar').addEventListener('click', async () => {
        const fd = new FormData();
        const senha = document.getElementById('senha').value.trim();

        if (!senha) {
            document.getElementById('error').textContent = 'Por favor, preencha a senha.';
            document.getElementById('senha').focus();
            return;
        }

        fd.append('senha', senha);
        const retorno = await fetch('/mykeeper/src/Controllers/admin_auth.php', {
            method: 'POST',
            body: fd
        });
        const resposta = await retorno.json();

        if (resposta.status === 'ok') {
            window.location.href = '/mykeeper/src/Views/admin_home.php';
        } else {
            document.getElementById('error').textContent = 'Erro: ' + resposta.mensagem + '. Redirecionando...';
            setTimeout(() => {
                window.location.href = '/mykeeper/src/Views/home.php';
            }, 2000);
        }
    });
});