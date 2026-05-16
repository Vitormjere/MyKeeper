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
    const response = await fetch('/mykeeper/config/check_session.php');
    const data = await response.json();
    if (!data.logado) {
        window.location.href = '/mykeeper/src/Views/usuario_login.php';
        return;
    }

    const urlParams = new URLSearchParams(window.location.search);
    const id_lista_compra = urlParams.get('id_lista_compra');
    if (!id_lista_compra) {
        window.location.href = '/mykeeper/src/Views/compras.php';
        return;
    }

    buscar(id_lista_compra);

    document.getElementById('adicionarItem').addEventListener('click', () => {
        window.location.href = `/mykeeper/src/Views/compras_itens_adicionar.php?id_lista_compra=${id_lista_compra}`;
    });
});

async function buscar(id_lista_compra) {

    const retornoLista = await fetch('/mykeeper/src/Controllers/compras_get.php?id=' + id_lista_compra);
    const respostaLista = await retornoLista.json();

    const status = respostaLista.data[0].status_compra;
    const bloqueado = status === 'concluida' || status === 'arquivada';

    if (bloqueado) {
        document.getElementById('adicionarItem').style.display = 'none';
    }

    const retornoItens = await fetch('/mykeeper/src/Controllers/compras_itens_get.php?id_lista_compra=' + id_lista_compra);
    const respostaItens = await retornoItens.json();

    if (respostaItens.status == 'ok') {
        preencherTabela(respostaItens.data, id_lista_compra, bloqueado);
    } else {
        document.getElementById('mensagem').textContent = 'Não há itens cadastrados nesta lista de compra.';
    }
}

function preencherTabela(tabela, id_lista_compra, bloqueado) {

    var html = "";

    for (var i = 0; i < tabela.length; i++) {

        html += `<div class="card">

                    <div class="card-nome">${e(tabela[i].nome)}</div>

                    <div class="card-data">
                        <p>Quantidade: ${respostaOuTracos(e(String(tabela[i].quantidade)))} ${e(tabela[i].und_medida)}</p>
                        <p>Categoria: ${respostaOuTracos(tabela[i].nome_categoria)}</p>
                    </div>

                    <div class="card-botoes">
                        ${bloqueado ? '' : `
                        <button class="btn-editar"><a href="compras_itens_alterar.php?id_lista_compra=${id_lista_compra}&id_produto=${tabela[i].id_produto}">Editar</a></button>
                        `}
                        <button class="btn-excluir"><a href="#" onclick="excluir(${tabela[i].id_lista_compra}, ${tabela[i].id_produto})">Excluir</a></button>
                    </div>

                </div>`;

    }

    document.getElementById('item').innerHTML = html;
}

async function excluir(id_lista_compra, id_produto) {
    if (!window.confirm('Tem certeza que deseja excluir este item?')) {
        return;
    }
    const retorno = await fetch(`/mykeeper/src/Controllers/compras_itens_excluir.php?id_lista_compra=${id_lista_compra}&id_produto=${id_produto}`);
    const resposta = await retorno.json();
    if (resposta.status == 'ok') {
        alert('SUCESSO! ' + resposta.mensagem);
    } else {
        alert('ERRO! ' + resposta.mensagem);
    }
    window.location.reload();
}