function e(str) {
    const div = document.createElement('div');
    div.appendChild(document.createTextNode(str));
    return div.innerHTML;
}

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
    buscar(data.id);
});

document.getElementById('alterarsenha').addEventListener('click', async () => {
    const senha = document.getElementById('senha').value.trim();
    const nova_senha = document.getElementById('nova_senha').value.trim();

    if (!senha) {
        document.getElementById('error-senha').textContent = 'Por favor, preencha a senha.';
        document.getElementById('senha').focus();
        return;
    }

    if (!nova_senha) {
        document.getElementById('error-nova_senha').textContent = 'Por favor, preencha a nova senha.';
        document.getElementById('nova_senha').focus();
        return;
    }else if(nova_senha.length < 8 || !/[A-Z]/.test(nova_senha) || !/[a-z]/.test(nova_senha) || !/[0-9]/.test(nova_senha) || !/[@$!%*?&]/.test(nova_senha)) {
        document.getElementById('error-nova_senha').textContent = 'ERRO! Senha deve conter no mínimo 8 caracteres, letras maiúsculas, minúsculas, números e caracteres especiais.';
        return;
    }

    const fd = new FormData();
    fd.append('senha', senha);
    fd.append('nova_senha', nova_senha);

    const retorno = await fetch('/mykeeper/src/Controllers/senha_alterar_post.php', {
        method: 'POST',
        body: fd
    });

    const resposta = await retorno.json();
    if (resposta.status == 'ok') {
        document.getElementById('error').style.color = '#00ffa3';
        document.getElementById('error').textContent = 'Senha atualizada com sucesso!' + '.Redirecionando...';
        setTimeout(() => {
            window.location.href = '/mykeeper/perfil_usuario';
        }, 1000);
    } else {
        document.getElementById('error').style.color = '#ff6b6b';
        document.getElementById('error').textContent = 'Erro: ' + resposta.mensagem;
    }
});

