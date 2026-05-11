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
    const response = await fetch('../../config/check_session.php');
    const data = await response.json();
    if (!data.logado) {
        window.location.href = 'usuario_login.php';
        return;
    }
    buscar(data.id);
});

async function buscar(id) {
    const retorno = await fetch(`../Controllers/usuario_get.php?id=${id}`);
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

    if (cepDigitsLength(cep) !== 8) {
        alert('Digite um CEP válido no formato 00000-000.');
        cepInput.focus();
        return;
    }

    const fd = new FormData();
    fd.append('nome', document.getElementById('nome').value.trim());
    fd.append('email', document.getElementById('email').value.trim());
    fd.append('cep', cep);

    const retorno = await fetch('../Controllers/usuario_alterar_post.php', {
        method: 'POST',
        body: fd
    });
    const resposta = await retorno.json();
    if (resposta.status == 'ok') {
        alert(resposta.mensagem);
        window.location.href = 'perfil_usuario.php';
    } else {
        alert('Erro: ' + resposta.mensagem);
    }
});
