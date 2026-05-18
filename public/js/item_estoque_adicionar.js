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

    const urlParams = new URLSearchParams(window.location.search);
    const id_estoque = urlParams.get('id_estoque');

    if (!id_estoque) {
        window.location.href = '/mykeeper/src/Views/estoque.php';
        return;
    }

    document.getElementById('id_estoque').value = id_estoque;
    buscarProdutos();
    document.getElementById('btn-voltar').href = '/mykeeper/src/Views/estoque_itens.php?id_estoque=' + id_estoque;
});

async function buscarProdutos() {
    const retorno = await fetch('/mykeeper/src/Controllers/produto_get.php');
    const resposta = await retorno.json();
    if (resposta.status == 'ok') {
        preencherLista(resposta.data);
    } else {
        document.getElementById('mensagem').textContent = 'Nenhum produto cadastrado.';
    }
}

function preencherLista(tabela) {
    var html = "";
    for (var i = 0; i < tabela.length; i++) {
        const imagem = tabela[i].imagem
            ? `<img src="${e(tabela[i].imagem)}" style="width:40px; height:40px;">`
            : 'Sem imagem';

        html += `<div class="card" id="card-${tabela[i].id}">
                    <div class="card-icone">
                        ${imagem}
                    </div>
                    <div class="card-nome">
                        ${e(tabela[i].nome)}
                    </div>
                    <div class="card-data">
                        ${e(tabela[i].und_medida)}
                    </div>
                    <div class="card-inputs">
                        <input type="number" id="quantidade-${tabela[i].id}" placeholder="Quantidade" min="0" step="0.01">
                        <input type="date"   id="validade-${tabela[i].id}"   placeholder="Validade">
                        <input type="text"   id="marca-${tabela[i].id}"      placeholder="Marca">
                    </div>
                    <div class="card-botoes">
                        <button class="btn-editar" onclick="adicionar(${tabela[i].id})">+ Adicionar</button>
                    </div>
                </div>`;
    }
    document.getElementById('item').innerHTML = html;
}

async function adicionar(id_produto) {
    const id_estoque  = document.getElementById('id_estoque').value;
    const quantidade  = document.getElementById('quantidade-' + id_produto).value;
    const data_validade = document.getElementById('validade-'   + id_produto).value;
    const marca       = document.getElementById('marca-'      + id_produto).value;

    const fd = new FormData();
    fd.append('id_estoque',   id_estoque);
    fd.append('id_produto',   id_produto);
    fd.append('quantidade',   quantidade);
    fd.append('data_validade', data_validade);
    fd.append('marca',        marca);

    const retorno = await fetch('/mykeeper/src/Controllers/item_estoque_novo_back.php', {
        method: 'POST',
        body: fd
    });
    const resposta = await retorno.json();

    if (resposta.status == 'ok') {
        notificacaoSistema('SUCESSO! ' + resposta.mensagem, 'success');
        setTimeout(function() {
            window.location.href = `/mykeeper/src/Views/estoque_itens.php?id_estoque=${id_estoque}`;
        }, 1200);
    } else {
        notificacaoSistema('ERRO! ' + resposta.mensagem, 'error');
    }
}
