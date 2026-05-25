const cepInput = document.getElementById('cep');

document.addEventListener('DOMContentLoaded', async () => {
    // 1. Primeiro verifica se está logado
    const response = await fetch('/mykeeper/config/check_session.php');
    const data = await response.json();
    
    if (!data.logado) {
        if (data.expirado) {
            window.location.href = '/mykeeper/src/Views/usuario_login.php?motivo=expirado';
        } else {
            window.location.href = '/mykeeper/src/Views/usuario_login.php';
        }
        return;
    }
});

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

document.getElementById('addsuporte').addEventListener('click', () => {
    novo();
});

cepInput.addEventListener('input', () => {
    cepInput.value = formatCep(cepInput.value);
});

async function novo() {
    const nome = document.getElementById('nome').value;
    const email = document.getElementById('email').value;
    const senha = document.getElementById('senha').value;
    const cep = formatCep(cepInput.value);

    if(!nome){
        document.getElementById('error-nome').textContent = 'Nome precisa receber valores';
        return;
    }

    if(!email){
        document.getElementById('error-email').textContent = 'Email precisa receber valores';
        return;
    }else if(!email.includes('@') && !email.includes('.')) {
        document.getElementById('error-email').textContent = 'Digite um email válido, no formato @xxx.xxx';
        return;
    }

    if (!cep || cepDigitsLength(cep) !== 8) {
        document.getElementById('error-cep').textContent = 'Digite um CEP válido no formato 00000-000.';
        cepInput.focus();
        return;
    }
    
    if(!senha){
        document.getElementById('error-senha').textContent = 'Senha precisa receber valores';
        return;
    }else if(senha.length < 8) {
        document.getElementById('error-senha').textContent = 'ERRO! Senha muito curta';
        return;
    }

    const fd = new FormData();
    fd.append('nome', nome);
    fd.append('email', email);
    fd.append('senha', senha);
    fd.append('cep', cep);
    const retorno = await fetch('/mykeeper/src/Controllers/suporte_novo_back.php', {
        method: 'POST',
        body: fd
    });

    const resposta = await retorno.json();

    if (resposta.status == 'ok') {
        document.getElementById('error').style.color = '#00ffa3';
        document.getElementById('error').innerText = 'SUCESSO! ' + resposta.mensagem + '. Redirecionando...';
        setTimeout(() => {
            window.location.href = '/mykeeper/src/Views/suporte.php';
        }, 1000);
    } else {
        document.getElementById('error').style.color = '#ff6b6b';
        document.getElementById('error').innerText = 'ERRO! ' + resposta.mensagem;
    }
}
