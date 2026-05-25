function e(str) {
    const div = document.createElement('div');
    div.appendChild(document.createTextNode(str));
    return div.innerHTML;
}

document.addEventListener('DOMContentLoaded', async () => {
    const response = await fetch('/mykeeper/config/check_session.php');
    const data = await response.json();

    if (!data.logado) {
        window.location.href = '/mykeeper/src/Views/usuario_login.php';
        return;
    }

    buscar(data.id); 
});

async function buscar(id) {
    const retorno = await fetch(`/mykeeper/src/Controllers/usuario_get.php?id=${id}`);
    const resposta = await retorno.json();
    if (resposta.status == 'ok') {
        preencherInformacoes(resposta.data);
    }
}

function preencherInformacoes(usuario) {
    document.getElementById('nome').textContent = e(usuario.nome);
    document.getElementById('email').textContent = e(usuario.email);
    document.getElementById('cep').textContent = e(usuario.cep);
    document.getElementById('linkEditar').href = `/mykeeper/src/Views/perfil_alterar.php?id=${usuario.id}`;
}

document.getElementById('desativarConta').addEventListener('click', async () => {
    confirmarSistema('Tem certeza que deseja desativar sua conta? Esta ação não pode ser desfeita.', async function() {
        const response = await fetch('/mykeeper/src/Controllers/usuario_desativar.php', {
            method: 'POST'
        });
        const data = await response.json();
        if (data.status === 'ok') {
            notificacaoSistema(data.mensagem, 'success');
            setTimeout(function() {
                window.location.href = '/mykeeper/src/Views/usuario_login.php';
            }, 1200);
        } else {
            notificacaoSistema('Erro ao desativar conta: ' + data.mensagem, 'error');
        }
    }, {
        textoConfirmar: 'Sim, desativar'
    });
});
