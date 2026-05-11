function e(str) {
    const div = document.createElement('div');
    div.appendChild(document.createTextNode(str));
    return div.innerHTML;
}

document.addEventListener('DOMContentLoaded', async ()=>{
    // 1. Primeiro verifica se está logado
    const response = await fetch('/mykeeper/config/check_session.php');
    const data = await response.json();
    
    if (!data.logado) {
        window.location.href = '/mykeeper/src/Views/usuario_login.php';
        return; // para a execução aqui
    }

    const urlParams = new URLSearchParams(window.location.search);
    const id = urlParams.get('id');

    document.getElementById('id').value = id;

    buscar(id);
});

async function buscar(id){
    const retorno = await fetch('/mykeeper/src/Controllers/compras_get.php?id='+id);
    const resposta = await retorno.json();

    if(resposta.status == 'ok'){
        var item = resposta.data[0];
        document.getElementById('titulo').value       = e(item.titulo);

    }else{
        document.getElementById('error').textContent = "ERRO: "+resposta.mensagem;
        window.location.href = 'compras.php';
    }
}

document.getElementById('addcompras').addEventListener('click', ()=>{
    alterar();
});

async function alterar(){
    let titulo       = document.getElementById('titulo').value;
    let id           = document.getElementById('id').value;
    
    if(!titulo){
        document.getElementById('error-titulo').textContent = 'Título precisa receber valores';
        return;
    }

    const fd = new FormData();

    fd.append('titulo', titulo);

    const retorno = await fetch('/mykeeper/src/Controllers/compras_alterar_back.php?id='+id, {
        method: 'POST',
        body: fd
    });

    const resposta = await retorno.json();

    if(resposta.status == 'ok'){
        const msg = document.getElementById('error');
        msg.style.color = '#00ffa3'; // Muda para verde em caso de sucesso
        msg.textContent = 'SUCESSO! ' + resposta.mensagem + '. Redirecionando...';
        setTimeout(() => {
            window.location.href = "/mykeeper/src/Views/compras.php";
        }, 1500);
    }else{
        const msg = document.getElementById('error');
        msg.style.color = '#ff4d4d'; // Muda para vermelho em caso de erro
        msg.textContent = 'ERRO! ' + resposta.mensagem;
    }
}