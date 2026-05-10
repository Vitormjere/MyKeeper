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

    buscar(id_estoque);

    document.getElementById('adicionarItem').addEventListener('click', () => {
        window.location.href = `/mykeeper/src/Views/item_estoque_adicionar.php?id_estoque=${id_estoque}`;
    });
});

async function buscar(id_estoque) {
    const retorno = await fetch('/mykeeper/src/Controllers/item_estoque_get.php?id_estoque=' + id_estoque);
    const resposta = await retorno.json();
    
    if (resposta.status == 'ok') {
        preencherTabela(resposta.data, id_estoque);
    } else {
        document.getElementById('mensagem').textContent = 'Não há itens cadastrados neste estoque.';
    }
}

function preencherTabela(tabela, id_estoque) {
    var html = "";
    for (var i = 0; i < tabela.length; i++) {
        const imagem = tabela[i].imagem
            ? `<img src="${e(tabela[i].imagem)}" style="width:40px; height:40px;">`
            : 'Sem imagem';

        const validade = tabela[i].data_validade
            ? new Date(tabela[i].data_validade).toLocaleDateString('pt-BR')
            : 'Sem validade';

        html += `<div class="card">
                    <div class="card-icone">
                        ${imagem}
                    </div>
                    <div class="card-nome">
                        ${e(tabela[i].nome)}
                    </div>
                    <div class="card-data">
                        Quantidade: ${e(String(tabela[i].quantidade))} ${e(tabela[i].und_medida)}
                    </div>
                    <div class="card-data">
                        Marca: ${e(tabela[i].marca ?? 'Não informada')}
                    </div>
                    <div class="card-data">
                        Validade: ${validade}
                    </div>
                    <div class="card-botoes">
                        <button class="btn-editar"><a href="item_estoque_alterar.php?id=${tabela[i].id}&id_estoque=${id_estoque}">Editar</a></button>
                        <button class="btn-excluir"><a href="#" onclick="excluir(${tabela[i].id}, ${id_estoque})">Excluir</a></button>
                    </div>
                </div>`;
    }
    document.getElementById('item').innerHTML = html;
}

async function excluir(id, id_estoque) {
    if (!window.confirm('Tem certeza que deseja excluir este item?')) {
        return;
    }

    const retorno = await fetch('/mykeeper/src/Controllers/item_estoque_excluir.php?id=' + id);
    const resposta = await retorno.json();

    if (resposta.status == 'ok') {
        alert('SUCESSO! ' + resposta.mensagem);
    } else {
        alert('ERRO! ' + resposta.mensagem);
    }

    buscar(id_estoque);
}