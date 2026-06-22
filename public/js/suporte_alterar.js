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


document.addEventListener('DOMContentLoaded', async ()=>{
    // 1. Primeiro verifica se está logado
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

    const urlParams = new URLSearchParams(window.location.search);
    const id = urlParams.get('id');

    document.getElementById('id').value = id;

    buscar(id);
});


async function buscar(id){
    const retorno = await fetch('/mykeeper/src/Controllers/suporte_get.php?id='+id);
    const resposta = await retorno.json();

    if(resposta.status == 'ok'){
        var item = resposta.data[0];

        document.getElementById('nome').value   = e(item.nome);
        document.getElementById('email').value  = e(item.email);
        cepInput.value = formatCep(item.cep);

    }else{
        notificacaoSistema('ERRO: ' + resposta.mensagem, 'error');
        setTimeout(function() {
            window.location.href = '/mykeeper/suporte';
        }, 1200);
    }
}

document.getElementById('alterarsuporte').addEventListener('click', ()=>{
    alterar();
});

async function alterar(){
    let nome   = document.getElementById('nome').value;
    let email  = document.getElementById('email').value;
    let id     = document.getElementById('id').value;
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
    
    if (cepDigitsLength(cep) !== 8) {
        document.getElementById('error-cep').textContent = 'Digite um CEP válido no formato 00000-000.';
        cepInput.focus();
        return;
    }

    const fd = new FormData();

    fd.append('nome', nome);
    fd.append('email', email);
    fd.append('cep', cep);

    const retorno = await fetch('/mykeeper/src/Controllers/suporte_alterar_back.php?id='+id, {
        method: 'POST',
        body: fd
    });

    const resposta = await retorno.json();

    if(resposta.status == 'ok'){
        document.getElementById('error').style.color = '#00ffa3';
        document.getElementById('error').innerText = 'SUCESSO! ' + resposta.mensagem + '. Redirecionando...';
        setTimeout(() => {
            window.location.href = "/mykeeper/suporte";
        }, 1000);
    }else{
        document.getElementById('error').style.color = '#ff6b6b';
        document.getElementById('error').innerText = 'ERRO! ' + resposta.mensagem;
    }
}
