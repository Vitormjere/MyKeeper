function e(str) {
    const div = document.createElement('div');
    div.appendChild(document.createTextNode(str));
    return div.innerHTML;
}

function formatCep(value) {
    let digits = String(value || '').replace(/\D/g, '').slice(0, 8);

    if (digits.length > 5) {
        digits = `${digits.slice(0, 5)}-${digits.slice(5)}`;
    }

    return digits;
}

function cepDigitsLength(value) {
    return String(value || '').replace(/\D/g, '').length;
}

const cepInput = document.getElementById('cep');

cepInput.addEventListener('input', () => {
    cepInput.value = formatCep(cepInput.value);
});

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
    document.getElementById('nome').value = usuario.nome;
    document.getElementById('email').value = usuario.email;
    cepInput.value = formatCep(usuario.cep);
}

document.getElementById('alterarperfil').addEventListener('click', async () => {
    const cep = formatCep(cepInput.value);
    const nome = document.getElementById('nome').value.trim();
    const email = document.getElementById('email').value.trim();

    if (!nome) {
        document.getElementById('error-nome').textContent = 'Por favor, preencha o nome.';
        document.getElementById('nome').focus();
        return;
    }

    if (!email) {
        document.getElementById('error-email').textContent = 'Por favor, preencha o email.';
        document.getElementById('email').focus();
        return;
    }else if(!email.includes('@') && !email.includes('.')) {
        document.getElementById('error-email').textContent = 'Por favor, preencha um email válido, no formato xxx@xxx.xxx';
        document.getElementById('email').focus();
        return;
    }
    
    if (cepDigitsLength(cep) !== 8) {
        document.getElementById('error-cep').textContent = 'Digite um CEP válido no formato 00000-000.';
        cepInput.focus();
        return;
    }

    const fd = new FormData();
    fd.append('nome', nome);
    fd.append('email', email);
    fd.append('cep', cep);

    const retorno = await fetch('/mykeeper/src/Controllers/usuario_alterar_post.php', {
        method: 'POST',
        body: fd
    });
    const resposta = await retorno.json();
    if (resposta.status == 'ok') {
        document.getElementById('error').textContent = 'Perfil atualizado com sucesso!' + '.Redirecionando...';
        setTimeout(() => {
            window.location.href = '/mykeeper/src/Views/perfil_usuario.php';
        }, 1000);
    } else {
        document.getElementById('error').textContent = 'Erro: ' + resposta.mensagem;
    }
});