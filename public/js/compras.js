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
    buscar();
})

async function buscar() {
    const retorno = await fetch('/mykeeper/src/Controllers/compras_get.php');
    const resposta = await retorno.json();
    if(resposta.status == 'ok'){
        preencherTabela(resposta.data);
    } else {
        document.getElementById('mensagem').textContent = 'Não há listas de compras cadastradas.';
    }
    
}

function preencherTabela(tabela){


    var html = "";


    for(var i=0;i<tabela.length;i++){

        html += `<div class="card" style="cursor:pointer;" onclick="window.location.href='compras_itens.php?id_compra=${tabela[i].id}'">
                    <div class="card-nome">
                        ${e(tabela[i].titulo)}
                    </div>
                    <div class="card-data">
                        Criado em: ${new Date(tabela[i].data_criacao).toLocaleDateString('pt-BR')}
                    </div>
                    <div class="card-botoes">
                        <button class="btn-editar" onclick="event.stopPropagation()"><a href="compras_alterar.php?id=${tabela[i].id}">Editar</a></button>
                        <button class="btn-excluir" onclick="event.stopPropagation(); excluir(${tabela[i].id})">Excluir</button>
                    </div>
                </div>`;
    }
        
    document.getElementById('item').innerHTML = html;
    

}

async function excluir(id){
    if (!window.confirm('Tem certeza que deseja excluir esta lista de compras?')) {
        return;
    }

    const retorno = await fetch('/mykeeper/src/Controllers/compras_excluir.php?id='+id);
    const resposta = await retorno.json();
    if(resposta.status == 'ok'){
        alert('SUCESSO! '+ resposta.mensagem);
    }else{
        alert('ERRO! ' + resposta.mensagem)
    }

    window.location.reload();
}

document.getElementById('criarCompras').addEventListener('click', ()=>{
    window.location.href = '/mykeeper/src/Views/compras_novo.php'
})

