document.addEventListener('DOMContentLoaded', async () => {
    // 1. Primeiro verifica se está logado
    const response = await fetch('../../config/check_session.php');
    const data = await response.json();
    
    if (!data.logado) {
        window.location.href = 'usuario_login.php';
        return; // para a execução aqui
    }
});


document.getElementById('criarTicket').addEventListener('click', () => {
    novo();
});

async function novo() {
    const titulo = document.getElementById('titulo').value;
    const descricao = document.getElementById('descricao').value;

    const fd = new FormData();
    fd.append('titulo', titulo);
    fd.append('descricao', descricao);

    const retorno = await fetch('../Controllers/ticket_novo_back.php', {
        method: 'POST',
        body: fd
    });

    const resposta = await retorno.json();

    if (resposta.status == 'ok') {
        alert('SUCESSO! ' + resposta.mensagem);
        window.location.href = 'ticket_usuario.php';
    } else {
        alert('ERRO! ' + resposta.mensagem);
    }
}
