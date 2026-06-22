function e(str) {
    const div = document.createElement('div');
    div.appendChild(document.createTextNode(str));
    return div.innerHTML;
}

function formatData(dataAmericana){
    const[ano, mes, dia] = dataAmericana.split('-');
    return `${dia}/${mes}/${ano}`;
}

function respostaOuTracos(valor) {
    if (valor === null || valor === undefined || String(valor).trim() === '' || String(valor).toLowerCase() === 'null') {
        return '---';
    }

    return e(valor);
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
    const retorno = await fetch('/mykeeper/src/Controllers/tickets_suporte_get.php');
    const resposta = await retorno.json();

    if (resposta.status == 'ok') {
        if (resposta.data.length === 0) {
            document.getElementById('mensagem').innerHTML = '<p>Nenhum ticket registrado no momento.</p>';
            return;
        }
        preencherTabela(resposta.data);
    } else {
        document.getElementById('mensagem').innerHTML = '<p>Erro ao carregar os tickets.</p>';
    }
}

function preencherTabela(tabela){
    var html = `
    <table class="tabela">
        <tr>
            <th> ID </th>
            <th> Titulo </th>
            <th> Descrição </th>
            <th> Data de Abertura </th>
            <th> Resposta </th>
            <th> Status </th>
            <th> # </th>
        </tr>
    `;

    for(var i=0;i<tabela.length;i++){

        html += `<tr>
                <td> ${tabela[i].id} </td>
                <td> ${e(tabela[i].titulo)} </td>
                <td> ${e(tabela[i].descricao)} </td>
                <td> ${e(formatData(tabela[i].data_ticket))} </td>
                <td> ${respostaOuTracos(tabela[i].resposta_ticket)} </td>
                <td> ${e(tabela[i].status_ticket)} </td>
                <td class="botoes"> 
                <button class="btn-editar"><a href="/mykeeper/tickets_suporte_alterar?id=${tabela[i].id}">Editar</a></button>
                <button class="btn-chat"><a href="/mykeeper/chat_ticket?id=${tabela[i].id}">Chat</a></button>
                </td>
                </tr>`;
    }

    html += `</table>`;
    document.getElementById('item').innerHTML = html
}

