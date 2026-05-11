document.getElementById('formLogin').addEventListener('submit',(e)=>{
    e.preventDefault();
    login();
})

document.getElementById('createAccount').addEventListener('click', ()=>{
    window.location.href = 'usuario_cadastro.php';
})

async function login() {
    let email = document.getElementById('email').value;
    let senha = document.getElementById('senha').value;

    const fd = new FormData();
    fd.append('email', email);
    fd.append('senha', senha);

    const retorno = await fetch('../Controllers/usuario_login.php',{
        method: "POST",
        body: fd
    })

    const resposta = await retorno.json();
    if(resposta.status == 'ok'){
        alert(resposta.mensagem);
        window.location.href = resposta.redirect
    }else{
        alert(resposta.mensagem);
    };
}

document.addEventListener('DOMContentLoaded', async ()=>{
    const response = await fetch('../../config/check_session.php');
    const data = await response.json();

    if(data.logado){
        window.location.href = data.redirect;
    };
});
