function e(str) {
    const div = document.createElement('div');
    div.appendChild(document.createTextNode(str));
    return div.innerHTML;
}

function respostaOuTracos(valor) {
    if (valor === null || valor === undefined || String(valor).trim() === '' || String(valor).toLowerCase() === 'null') {
        return '---';
    }

    return e(valor);
}

document.addEventListener('DOMContentLoaded', async () => {
    const response = await fetch('../../config/check_session.php');
    const data = await response.json();

    if (!data.logado) {
        window.location.href = 'usuario_login.php';
        return;
    }

    buscar(data.id); 
});

async function buscar(id) {
    const retorno = await fetch(`../Controllers/ticket_get.php?id=${id}`);
    const resposta = await retorno.json();
    if (resposta.status == 'ok') {
        preencherTabela(resposta.data);
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
                <td> ${e(tabela[i].data_ticket)} </td>
                <td> ${respostaOuTracos(tabela[i].resposta_ticket)} </td>
                <td> ${e(tabela[i].status_ticket)} </td>
                <td class="botoes"> 
                <button class = "btn-editar"><a href="ticket_usuario_alterar.php?id=${tabela[i].id}">Editar</a></button>
                <button class = "btn-excluir"><a href="#" onclick="excluir(${tabela[i].id})">Excluir</a></button>
                </td>
                </tr>`;
    }

    html += `</table>`;
    document.getElementById('item').innerHTML = html
}

async function excluir(id){
    const retorno = await fetch('../Controllers/ticket_excluir.php?id='+id);
    const resposta = await retorno.json();
    if(resposta.status == 'ok'){
        alert('SUCESSO! '+ resposta.mensagem);
    }else{
        alert('ERRO! ' + resposta.mensagem)
    }

    window.location.reload();
}

document.getElementById('ticket_novo').addEventListener('click', ()=>{
    window.location.href = 'ticket_usuario_novo.php'
});
