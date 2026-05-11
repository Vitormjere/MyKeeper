document.addEventListener('DOMContentLoaded', async () => {
    // 1. Primeiro verifica se está logado
    const response = await fetch('../../config/check_session.php');
    const data = await response.json();
    
    if (!data.logado) {
        window.location.href = 'usuario_login.php';
        return; // para a execução aqui
    }
}); 

document.getElementById('entrar').addEventListener('click', async () => {
        const fd = new FormData();
        fd.append('senha', document.getElementById('senha').value);

        const retorno = await fetch('../Controllers/admin_auth.php', {
            method: 'POST',
            body: fd
        });
        const resposta = await retorno.json();

        if (resposta.status === 'ok') {
            window.location.href = 'admin_home.php';
        } else {
            alert('Erro: ' + resposta.mensagem);
        }
    });
