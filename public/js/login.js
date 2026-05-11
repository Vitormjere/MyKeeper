document.getElementById('formLogin').addEventListener('submit',(e)=>{
    e.preventDefault();
    login();
})

document.getElementById('createAccount').addEventListener('click', ()=>{
    window.location.href = '/mykeeper/src/Views/usuario_cadastro.php';
})

async function login() {
    let email = document.getElementById('email').value;
    let senha = document.getElementById('senha').value;

    if(!email){
        document.getElementById('error-email').textContent = 'Email precisa receber valores';
        return;
    }else if(!email.includes('@') && !email.includes('.')) {
        document.getElementById('error-email').textContent = 'Digite um email válido, no formato @xxx.xxx';
        return;
    }

    if(!senha){
        document.getElementById('error-senha').textContent = 'Senha precisa receber valores'
    }else if(senha.length < 8) {
        document.getElementById('error-senha').textContent = 'ERRO! Senha muito curta';
        return;
    }

    const fd = new FormData();
    fd.append('email', email);
    fd.append('senha', senha);

    const retorno = await fetch('/mykeeper/src/Controllers/usuario_login.php',{
        method: "POST",
        body: fd
    })

    const resposta = await retorno.json();
    if(resposta.status == 'ok'){
        document.getElementById('error').textContent = 'SUCESSO! ' + resposta.mensagem + '. Redirecionando...';
        setTimeout(() => {
            window.location.href = resposta.redirect;
        }, 1000);
    }else{
        document.getElementById('error').textContent = 'ERRO! ' + resposta.mensagem;
    };
}

document.addEventListener('DOMContentLoaded', async ()=>{
    const response = await fetch('/mykeeper/config/check_session.php');
    const data = await response.json();

    if(data.logado){
        window.location.href = data.redirect;
    };
});