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
    buscar();
})

async function buscar() {
    const retorno = await fetch('../Controllers/produto_get.php');
    const resposta = await retorno.json();
    if(resposta.status == 'ok'){
        preencherTabela(resposta.data);
    }
    
}

function preencherTabela(tabela){
    var html = `
    <table class="tabela">
        <tr>
            <th> ID </th>
            <th>Ícone</th>
            <th> Nome </th>
            <th> Categoria </th>
            <th> Unidade Medida </th>
            <th> # </th>
        </tr>
    `;

    for(var i=0;i<tabela.length;i++){
        const icone = tabela[i].imagem
            ? `<img src="${e(normalizarCaminhoArquivo(tabela[i].imagem))}" style="width:40px; height:40px;">`
            : 'Sem ícone';

        html += `<tr>
                <td> ${tabela[i].id} </td>
                <td> ${icone} </td>
                <td> ${e(tabela[i].nome)} </td>
                <td> ${e(tabela[i].categoria)} </td>
                <td> ${e(tabela[i].und_medida)} </td>
                <td class="botoes"> 
                <button class = "btn-editar"><a href="produto_alterar.php?id=${tabela[i].id}">Editar</a></button>
                <button class = "btn-excluir"><a href="#" onclick="excluir(${tabela[i].id})">Excluir</a></button>
                </td>
                </tr>`;
    }

    html += `</table>`;
    document.getElementById('item').innerHTML = html
}

async function excluir(id){
    const retorno = await fetch('../Controllers/produto_excluir.php?id='+id);
    const resposta = await retorno.json();
    if(resposta.status == 'ok'){
        alert('SUCESSO! '+ resposta.mensagem);
    }else{
        alert('ERRO! ' + resposta.mensagem)
    }

    window.location.reload();
}

document.getElementById('produto_novo').addEventListener('click', ()=>{
    window.location.href = 'produto_novo.php'
})
