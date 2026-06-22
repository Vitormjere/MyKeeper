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

document.getElementById('icone_estoque').addEventListener('change', function(){
    const file = this.files[0];
    if(file){
        const reader = new FileReader();
        reader.onload = function(e){
            const preview = document.getElementById('preview');
            preview.src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(file);
    }
});

async function buscar(id){
    const retorno = await fetch('/mykeeper/src/Controllers/estoque_get.php?id='+id);
    const resposta = await retorno.json();

    if(resposta.status == 'ok'){
        var item = resposta.data[0];

        document.getElementById('nome_estoque').value       = e(item.nome_estoque);

        if(item.icone_estoque){
            const preview = document.getElementById('preview');
            preview.src = item.icone_estoque; 
            preview.style.display = 'block';
        }

    }else{
        document.getElementById('error').textContent = "ERRO: "+resposta.mensagem;
        window.location.href = 'estoque.php';
    }
}

document.getElementById('alterarestoque').addEventListener('click', ()=>{
    alterar();
});

async function alterar(){
    let nome_estoque       = document.getElementById('nome_estoque').value;
    let id                 = document.getElementById('id').value;
    let icone_estoque      = document.getElementById('icone_estoque').files[0];

    if(!nome_estoque){
        document.getElementById('error-nome').textContent = 'Nome precisa receber valores';
        return;
    }

    const fd = new FormData();

    fd.append('nome_estoque', nome_estoque);

    // ENVIA IMAGEM SE EXISTIR
    if(icone_estoque){
        fd.append('icone_estoque', icone_estoque);
    }

    const retorno = await fetch('/mykeeper/src/Controllers/estoque_alterar_back.php?id='+id, {
        method: 'POST',
        body: fd
    });

    const resposta = await retorno.json();

    if(resposta.status == 'ok'){
        const msg = document.getElementById('error');
        msg.style.color = '#00ffa3'; // Muda para verde em caso de sucesso
        msg.textContent = 'SUCESSO! ' + resposta.mensagem + '. Redirecionando...';
        setTimeout(() => {
            window.location.href = "/mykeeper/estoque";
        }, 1500);
    }else{
        const msg = document.getElementById('error');
        msg.style.color = '#ff4d4d'; // Muda para vermelho em caso de erro
        msg.textContent = 'ERRO! ' + resposta.mensagem;
    }
}