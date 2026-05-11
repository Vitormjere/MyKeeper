const cadastroForm = document.getElementById('formCadastro');
const entrarButton = document.getElementById('entrar');
const cepInput = document.getElementById('cep');

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

cadastroForm.addEventListener('submit', (event) => {
    event.preventDefault();
    cadastrar();
});

entrarButton.addEventListener('click', () => {
    window.location.href = 'usuario_login.php';
});

cepInput.addEventListener('input', () => {
    cepInput.value = formatCep(cepInput.value);
});

async function cadastrar() {
    const nome = document.getElementById('nome').value.trim();
    const email = document.getElementById('email').value.trim();
    const senha = document.getElementById('senha').value;
    const cep = formatCep(cepInput.value);

    if (cepDigitsLength(cep) !== 8) {
        alert('ERRO! Digite um CEP válido no formato 00000-000.');
        cepInput.focus();
        return;
    }

    if (senha.length < 8) {
        alert('ERRO! Senha muito curta');
        return;
    }

    const fd = new FormData();
    fd.append('nome', nome);
    fd.append('email', email);
    fd.append('senha', senha);
    fd.append('cep', cep);

    const retorno = await fetch('../Controllers/usuario_cadastrar.php', {
        method: 'POST',
        body: fd
    });

    const resposta = await retorno.json();
    if (resposta.status === 'ok') {
        alert('SUCESSO! Cadastro realizado com êxito');
        window.location.href = 'usuario_login.php';
        return;
    }

    alert('ERRO! ' + resposta.mensagem);
}
