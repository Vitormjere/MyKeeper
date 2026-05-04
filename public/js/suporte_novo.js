document.addEventListener('DOMContentLoaded', async () => {
    // 1. Primeiro verifica se está logado
    const response = await fetch('/mykeeper/config/check_session.php');
    const data = await response.json();
    
    if (!data.logado) {
        window.location.href = '/mykeeper/src/Views/usuario_login.php';
        return; // para a execução aqui
    }
});


document.getElementById('addsuporte').addEventListener('click', () => {
    novo();
});

async function novo() {
    const nome = document.getElementById('nome').value;
    const email = document.getElementById('email').value;
    const senha = document.getElementById('senha').value;

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

    const retorno = await fetch('/mykeeper/src/Controllers/suporte_novo_back.php', {
        method: 'POST',
        body: fd
    });

    const resposta = await retorno.json();

    if (resposta.status == 'ok') {
        document.getElementById('error').innerText = 'SUCESSO! ' + resposta.mensagem + '. Redirecionando...';
        setTimeout(() => {
            window.location.href = '/mykeeper/src/Views/suporte.php';
        }, 1000);
    } else {
        document.getElementById('error').innerText = 'ERRO! ' + resposta.mensagem;
    }
}