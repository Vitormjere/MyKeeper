document.addEventListener('DOMContentLoaded', async () => {
    // 1. Primeiro verifica se está logado
    const response = await fetch('/mykeeper/config/check_session.php');
    const data = await response.json();
    
    if (!data.logado) {
        window.location.href = '/mykeeper/src/Views/usuario_login.php';
        return; // para a execução aqui
    }
});


document.getElementById('criarTicket').addEventListener('click', () => {
    novo();
});

async function novo() {
    const titulo = document.getElementById('titulo').value;
    const descricao = document.getElementById('descricao').value;

    if (!titulo.trim()) {   
        document.getElementById('error-nome').textContent = 'Por favor, preencha o título do ticket.';
        document.getElementById('titulo').focus();
        return;
    }

    if (!descricao.trim()) {
        document.getElementById('error-descricao').textContent = 'Por favor, preencha a descrição do ticket.';
        document.getElementById('descricao').focus();
        return; 
    }

    const fd = new FormData();
    fd.append('titulo', titulo);
    fd.append('descricao', descricao);

    const retorno = await fetch('/mykeeper/src/Controllers/ticket_novo_back.php', {
        method: 'POST',
        body: fd
    });

    const resposta = await retorno.json();

    if (resposta.status == 'ok') {
        document.getElementById('error').textContent = 'SUCESSO! ' + resposta.mensagem + '. Redirecionando...';
        setTimeout(() => {
        window.location.href = '/mykeeper/src/Views/ticket_usuario.php';}, 1000);
    } else {
        document.getElementById('error').textContent = 'ERRO! ' + resposta.mensagem;
    }
}