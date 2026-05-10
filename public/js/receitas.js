function e(str) {
    const div = document.createElement('div');
    div.appendChild(document.createTextNode(str));
    return div.innerHTML;
}

document.addEventListener('DOMContentLoaded', async () => {
    const response = await fetch('/mykeeper/config/check_session.php');
    const data = await response.json();

    if (!data.logado) {
        window.location.href = '/mykeeper/src/Views/usuario_login.php';
        return;
    }

    buscar();
});

async function buscar() {
    const retorno = await fetch('/mykeeper/src/Controllers/receitas_get.php');
    const resposta = await retorno.json();

    if (resposta.status == 'ok') {
        preencherTabela(resposta.data);
    } else {
        document.getElementById('mensagem').textContent = 'Não há receitas cadastradas.';
    }
}

function preencherTabela(tabela) {
    var html = `
    <table class="tabela">
        <tr>
            <th>ID</th>
            <th>Título</th>
            <th>Descrição</th>
            <th>Gerada por IA</th>
            <th>Data de Geração</th>
            <th>#</th>
        </tr>
    `;

    for (var i = 0; i < tabela.length; i++) {
        html += `<tr>
            <td>${tabela[i].id}</td>
            <td>${e(tabela[i].titulo)}</td>
            <td>${e(tabela[i].descricao ?? '-')}</td>
            <td>${tabela[i].gerada_por_ia ? 'Sim' : 'Não'}</td>
            <td>${e(tabela[i].data_geracao)}</td>
            <td class="botoes">
                <button class="btn-editar"><a href="receitas_alterar.php?id=${tabela[i].id}">Editar</a></button>
                <button class="btn-excluir"><a href="#" onclick="excluir(${tabela[i].id})">Excluir</a></button>
            </td>
        </tr>`;
    }

    html += `</table>`;
    document.getElementById('item').innerHTML = html;
}

async function excluir(id) {
    if (!confirm('Tem certeza que deseja excluir esta receita?')) return;

    const retorno = await fetch('/mykeeper/src/Controllers/receitas_excluir.php?id=' + id);
    const resposta = await retorno.json();

    if (resposta.status == 'ok') {
        alert('SUCESSO! ' + resposta.mensagem);
    } else {
        alert('ERRO! ' + resposta.mensagem);
    }

    window.location.reload();
}

document.getElementById('receita_nova').addEventListener('click', () => {
    window.location.href = '/mykeeper/src/Views/receitas_novo.php';
});