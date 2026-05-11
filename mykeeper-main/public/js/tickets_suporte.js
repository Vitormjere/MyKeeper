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
    const retorno = await fetch('../Controllers/tickets_suporte_get.php');
    const resposta = await retorno.json();

    if (resposta.status == 'ok') {
        if (resposta.data.length === 0) {
            document.getElementById('item').innerHTML = '<p>Nenhum ticket registrado no momento.</p>';
            return;
        }
        preencherTabela(resposta.data);
    } else {
        document.getElementById('item').innerHTML = '<p>Erro ao carregar os tickets.</p>';
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
                <button class = "btn-editar"><a href="tickets_suporte_alterar.php?id=${tabela[i].id}">Editar</a></button>
                </td>
                </tr>`;
    }

    html += `</table>`;
    document.getElementById('item').innerHTML = html
}
