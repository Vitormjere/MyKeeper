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
    buscar();
})

async function buscar() {
    const retorno = await fetch('../Controllers/suporte_get.php');
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
            <th> Nome </th>
            <th> Email </th>
            <th> # </th>
        </tr>
    `;

    for(var i=0;i<tabela.length;i++){

        html += `<tr>
                <td> ${tabela[i].id} </td>
                <td> ${e(tabela[i].nome)} </td>
                <td> ${e(tabela[i].email)} </td>
                <td class="botoes"> 
                <button class = "btn-editar"><a href="suporte_alterar.php?id=${tabela[i].id}">Editar</a></button>
                <button class = "btn-excluir"><a href="#" onclick="excluir(${tabela[i].id})">Excluir</a></button>
                </td>
                </tr>`;
    }

    html += `</table>`;
    document.getElementById('item').innerHTML = html
}

async function excluir(id){
    const retorno = await fetch('../Controllers/suporte_excluir.php?id='+id);
    const resposta = await retorno.json();
    if(resposta.status == 'ok'){
        alert('SUCESSO! '+ resposta.mensagem);
    }else{
        alert('ERRO! ' + resposta.mensagem)
    }

    window.location.reload();
}

document.getElementById('suporte_novo').addEventListener('click', ()=>{
    window.location.href = 'suporte_novo.php'
})
