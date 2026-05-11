function e(str) {
    const div = document.createElement('div');
    div.appendChild(document.createTextNode(str));
    return div.innerHTML;
}

function normalizarCaminhoArquivo(caminho) {
    if (!caminho) {
        return caminho;
    }

    return caminho.replace(/^\/mykeeper\//, '../../');
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
    const retorno = await fetch('../Controllers/categoria_get.php?id='+id);
    const resposta = await retorno.json();

    if(resposta.status == 'ok'){
        var item = resposta.data[0];

        document.getElementById('nome_categoria').value       = e(item.nome);
        document.getElementById('descricao_categoria').value  = e(item.descricao);

        if(item.icone){
            const preview = document.getElementById('preview');
            preview.src = normalizarCaminhoArquivo(item.icone); 
            preview.style.display = 'block';
        }

    }else{
        alert("ERRO: "+resposta.mensagem)
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

    const fd = new FormData();

    fd.append('nome_categoria', nome_categoria);
    fd.append('descricao_categoria', descricao_categoria);

    // ENVIA IMAGEM SE EXISTIR
    if(icone){
        fd.append('icone_categoria', icone);
    }

    const retorno = await fetch('../Controllers/categoria_alterar_back.php?id='+id, {
        method: 'POST',
        body: fd
    });

    const resposta = await retorno.json();

    if(resposta.status == 'ok'){
        alert('SUCESSO! ' + resposta.mensagem);
        window.location.href = 'categoria.php';
    }else{
        alert('ERRO! ' + resposta.mensagem);
    }
}
