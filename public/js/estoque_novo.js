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

document.getElementById('icone_estoque').addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            const preview = document.getElementById('preview');
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
});

document.getElementById('addestoque').addEventListener('click', () => {
    novo();
});

async function novo() {
    const nome_estoque = document.getElementById('nome_estoque').value.trim();
    const icone_estoque = document.getElementById('icone_estoque').files[0];

    if (!nome_estoque) {
        document.getElementById('error-nome').textContent = 'Por favor, preencha o nome do estoque.';
        document.getElementById('nome_estoque').focus();
        return;
    }
    
    const fd = new FormData();
    fd.append('nome_estoque', nome_estoque);

    if (icone_estoque) {
        fd.append('icone_estoque', icone_estoque);
    }

    const retorno = await fetch('/mykeeper/src/Controllers/estoque_novo_back.php', {
        method: 'POST',
        body: fd
    });

    const resposta = await retorno.json();

    if (resposta.status == 'ok') {
        const msg = document.getElementById('error');
        msg.style.color = '#00ffa3'; // Muda para verde em caso de sucesso
        msg.textContent = 'SUCESSO! ' + resposta.mensagem + '. Redirecionando...';
        setTimeout(() => {
            window.location.href = '/mykeeper/estoque';
        }, 1500);
    } else {
        const msg = document.getElementById('error');
        msg.style.color = '#ff4d4d'; // Muda para vermelho em caso de erro
        msg.textContent = 'ERRO! ' + resposta.mensagem;
    }
}
    