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

document.getElementById('icone_categoria').addEventListener('change', function(){
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
    const retorno = await fetch('/mykeeper/src/Controllers/categoria_get.php?id='+id);
    const resposta = await retorno.json();

    if(resposta.status == 'ok'){
        var item = resposta.data[0];

        document.getElementById('nome_categoria').value       = e(item.nome);
        document.getElementById('descricao_categoria').value  = e(item.descricao);

        if(item.icone){
            const preview = document.getElementById('preview');
            preview.src = item.icone; 
            preview.style.display = 'block';
        }

    }else{
        document.getElementById('error').textContent = "ERRO: "+resposta.mensagem;
        window.location.href = 'categoria.php';
    }
}

document.getElementById('alterarcategoria').addEventListener('click', ()=>{
    alterar();
});

async function alterar(){
    let nome_categoria       = document.getElementById('nome_categoria').value;
    let descricao_categoria  = document.getElementById('descricao_categoria').value;
    let id                   = document.getElementById('id').value;
    let icone                = document.getElementById('icone_categoria').files[0];

    if(!nome_categoria){
        document.getElementById('error-nome').textContent = 'Nome precisa receber valores';
        return;
    }

    if(!descricao_categoria){
        document.getElementById('error-descricao').textContent = 'Descrição precisa receber valores';
        return;
    }

    const fd = new FormData();

    fd.append('nome_categoria', nome_categoria);
    fd.append('descricao_categoria', descricao_categoria);

    // ENVIA IMAGEM SE EXISTIR
    if(icone){
        fd.append('icone_categoria', icone);
    }

    const retorno = await fetch('/mykeeper/src/Controllers/categoria_alterar_back.php?id='+id, {
        method: 'POST',
        body: fd
    });

    const resposta = await retorno.json();

    if(resposta.status == 'ok'){
        document.getElementById('error').style.color = '#00ffa3';
        document.getElementById('error').textContent = 'SUCESSO! ' + resposta.mensagem + '. Redirecionando...';
        setTimeout(() => {
            window.location.href = "/mykeeper/src/Views/categoria.php";
        }, 1000);
    }else{
        document.getElementById('error').style.color = '#ff6b6b';
        document.getElementById('error').textContent = 'ERRO! ' + resposta.mensagem;
    }
}
