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
    const id_lista_compra = urlParams.get('id_lista_compra');

    if (!id_lista_compra) {
        window.location.href = '/mykeeper/src/Views/compras.php';
        return;
    }

    document.getElementById('id_lista_compra').value = id_lista_compra;
    buscarProdutos();
    document.getElementById('btn-voltar').href = '/mykeeper/src/Views/compras_itens.php?id_lista_compra=' + id_lista_compra;
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
                    <div class="card-categoria">
                        <p>Categoria: ${tabela[i].categoria}</p> <br>
                    </div>
                    <div class="card-inputs">
                        <input type="number" id="quantidade-${tabela[i].id}" placeholder="Quantidade" min="0" step="0.01">
                    </div>
                    <div class="card-botoes">
                        <button class="btn-editar" onclick="adicionar(${tabela[i].id})">+ Adicionar</button>
                    </div>
                    <hr>
                </div>`;
    }
    document.getElementById('item').innerHTML = html;
}

async function adicionar(id_produto) {
    const id_lista_compra = document.getElementById('id_lista_compra').value;
    const quantidade  = document.getElementById('quantidade-' + id_produto).value;

    const fd = new FormData();
    fd.append('id_lista_compra', id_lista_compra);
    fd.append('id_produto',   id_produto);
    fd.append('quantidade',   quantidade);
    const retorno = await fetch('/mykeeper/src/Controllers/compras_itens_adicionar_back.php', {
        method: 'POST',
        body: fd
    });
    const resposta = await retorno.json();

    if (resposta.status == 'ok') {
        notificacaoSistema('SUCESSO! ' + resposta.mensagem, 'success');
        setTimeout(function() {
            window.location.href = `/mykeeper/src/Views/compras_itens.php?id_lista_compra=${id_lista_compra}`;
        }, 1200);
    } else {
        notificacaoSistema('ERRO! ' + resposta.mensagem, 'error');
    }
}
