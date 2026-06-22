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
    buscar();
})

async function buscar() {
    const retorno = await fetch('/mykeeper/src/Controllers/suporte_get.php');
    const resposta = await retorno.json();
    if(resposta.status == 'ok'){
        preencherTabela(resposta.data);
    } else{
        document.getElementById('error').innerText = resposta.mensagem +'. Adicione um suporte para começar a usar!';
    }
    
}

function preencherTabela(tabela){
    var html = `
    <table class="tabela">
        <tr>
            <th> ID </th>
            <th> Nome </th>
            <th> Email </th>
            <th> CEP </th>
            <th> # </th>
        </tr>
    `;

    for(var i=0;i<tabela.length;i++){

        html += `<tr>
                <td> ${tabela[i].id} </td>
                <td> ${e(tabela[i].nome)} </td>
                <td> ${e(tabela[i].email)} </td>
                <td> ${e(tabela[i].cep)} </td>
                <td class="botoes"> 
                <button class="btn-editar"><a href="/mykeeper/suporte_alterar?id=${tabela[i].id}">Editar</a></button>
                <button class = "btn-excluir"><a href="#" onclick="excluir(${tabela[i].id})">Excluir</a></button>
                </td>
                </tr>`;
    }

    html += `</table>`;
    document.getElementById('item').innerHTML = html
}

async function excluir(id) {
    notificacaoExcluir('Tem certeza que deseja excluir este suporte?', 'confirm', async function() {
        const retorno = await fetch('/mykeeper/src/Controllers/suporte_excluir.php?id=' + id);
        const resposta = await retorno.json();
        if (resposta.status == 'ok') {
            notificacaoExcluir(resposta.mensagem, 'success');
            setTimeout(function() { window.location.reload(); }, 1500);
        } else {
            notificacaoExcluir(resposta.mensagem, 'error');
        }
    });
}

document.getElementById('suporte_novo').addEventListener('click', ()=>{
    window.location.href = '/mykeeper/suporte_novo'
})

