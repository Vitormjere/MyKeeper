document.addEventListener('DOMContentLoaded', async () => {
    // 1. Primeiro verifica se está logado
    const response = await fetch('../../config/check_session.php');
    const data = await response.json();
    
    if (!data.logado) {
        window.location.href = 'usuario_login.php';
        return; // para a execução aqui
    }
});


document.getElementById('addsuporte').addEventListener('click', () => {
    novo();
});

async function novo() {
    const nome = document.getElementById('nome').value;
    const email = document.getElementById('email').value;
    const senha = document.getElementById('senha').value;

    const fd = new FormData();
    fd.append('nome', nome);
    fd.append('email', email);
    fd.append('senha', senha);

    const retorno = await fetch('../Controllers/suporte_novo_back.php', {
        method: 'POST',
        body: fd
    });

    const resposta = await retorno.json();

    if (resposta.status == 'ok') {
        alert('SUCESSO! ' + resposta.mensagem);
        window.location.href = 'suporte.php';
    } else {
        alert('ERRO! ' + resposta.mensagem);
    }
}
