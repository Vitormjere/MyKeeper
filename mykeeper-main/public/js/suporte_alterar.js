function e(str) {
    const div = document.createElement('div');
    div.appendChild(document.createTextNode(str));
    return div.innerHTML;
}

document.addEventListener('DOMContentLoaded', async ()=>{
    // 1. Primeiro verifica se está logado
    const response = await fetch('../../config/check_session.php');
    const data = await response.json();
    
    if (!data.logado) {
        window.location.href = 'usuario_login.php';
        return; // para a execução aqui
    }

    const urlParams = new URLSearchParams(window.location.search);
    const id = urlParams.get('id');

    document.getElementById('id').value = id;

    buscar(id);
});


async function buscar(id){
    const retorno = await fetch('../Controllers/suporte_get.php?id='+id);
    const resposta = await retorno.json();

    if(resposta.status == 'ok'){
        var item = resposta.data[0];

        document.getElementById('nome').value   = e(item.nome);
        document.getElementById('email').value  = e(item.email);

    }else{
        alert("ERRO: "+resposta.mensagem)
        window.location.href = 'suporte.php';
    }
}

document.getElementById('alterarsuporte').addEventListener('click', ()=>{
    alterar();
});

async function alterar(){
    let nome   = document.getElementById('nome').value;
    let email  = document.getElementById('email').value;
    let id     = document.getElementById('id').value;

    const fd = new FormData();

    fd.append('nome', nome);
    fd.append('email', email);

    const retorno = await fetch('../Controllers/suporte_alterar_back.php?id='+id, {
        method: 'POST',
        body: fd
    });

    const resposta = await retorno.json();

    if(resposta.status == 'ok'){
        alert('SUCESSO! ' + resposta.mensagem);
        window.location.href = 'suporte.php';
    }else{
        alert('ERRO! ' + resposta.mensagem);
    }
}
