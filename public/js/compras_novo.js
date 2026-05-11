document.getElementById('addcompras').addEventListener('click', () => {
    novo();
});

async function novo() {
    const titulo = document.getElementById('titulo').value.trim();

    if (!titulo) {
        document.getElementById('error-nome').textContent = 'Por favor, preencha o título da lista de compras.';
        document.getElementById('titulo').focus();
        return;
    }
    
    const fd = new FormData();
    fd.append('titulo', titulo);

    const retorno = await fetch('/mykeeper/src/Controllers/compras_novo_back.php', {
        method: 'POST',
        body: fd
    });

    const resposta = await retorno.json();

    if (resposta.status == 'ok') {
        const msg = document.getElementById('error');
        msg.style.color = '#00ffa3'; // Muda para verde em caso de sucesso
        msg.textContent = 'SUCESSO! ' + resposta.mensagem + '. Redirecionando...';
        setTimeout(() => {
            window.location.href = '/mykeeper/src/Views/compras.php';
        }, 1500);
    } else {
        const msg = document.getElementById('error');
        msg.style.color = '#ff4d4d'; // Muda para vermelho em caso de erro
        msg.textContent = 'ERRO! ' + resposta.mensagem;
    }
}
    