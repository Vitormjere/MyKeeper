function e(str) {
    const div = document.createElement('div');
    div.appendChild(document.createTextNode(str));
    return div.innerHTML;
}

function atualizarVisualSelect(select) {
    select.dataset.empty = select.value ? 'false' : 'true';
}

document.addEventListener('DOMContentLoaded', async () => {
    document.querySelectorAll('select').forEach(select => {
        atualizarVisualSelect(select);
        select.addEventListener('change', () => atualizarVisualSelect(select));
    });

    const response = await fetch('/mykeeper/config/check_session.php');
    const data = await response.json();

    if (!data.logado) {
        window.location.href = '/mykeeper/src/Views/usuario_login.php';
        return;
    }

    const urlParams = new URLSearchParams(window.location.search);
    const id = urlParams.get('id');
    carregarProdutos(id);
});

async function carregarProdutos(id) {
    const retorno = await fetch('/mykeeper/src/Controllers/produto_get.php');
    const resposta = await retorno.json();

    window._produtos = resposta.status == 'ok' ? resposta.data : [];

    buscar(id);
}

function criarLinhaIngrediente(produtoSelecionado = '', qtd = '', und = '') {
    const modelo = document.getElementById('modelo-ingrediente');
    const clone = modelo.cloneNode(true);
    clone.removeAttribute('id');
    clone.style.display = '';

    const select = clone.querySelector('.select-produto');
    window._produtos.forEach(p => {
        const option = document.createElement('option');
        option.value = p.id;
        option.textContent = p.nome;
        if (p.id == produtoSelecionado) option.selected = true;
        select.appendChild(option);
    });

    atualizarVisualSelect(select);
    select.addEventListener('change', () => atualizarVisualSelect(select));

    clone.querySelector('.input-qtd').value = qtd;
    clone.querySelector('.input-und').value = und;

    clone.querySelector('.btn-remover').addEventListener('click', () => {
        clone.remove();
    });

    return clone;
}

async function buscar(id) {
    const retorno = await fetch('/mykeeper/src/Controllers/receitas_get.php?id=' + id);
    const resposta = await retorno.json();

    if (resposta.status == 'ok') {
        const item = resposta.data;
        document.getElementById('id').value = id;
        document.getElementById('titulo').value = e(item.titulo);
        document.getElementById('descricao').value = e(item.descricao ?? '');

        const lista = document.getElementById('lista-ingredientes');
        item.ingredientes.forEach(ing => {
            lista.appendChild(criarLinhaIngrediente(ing.id_produto, ing.qtd, ing.und_medida));
        });
    } else {
        document.getElementById('error').textContent = 'ERRO: ' + resposta.mensagem;
        window.location.href = '/mykeeper/src/Views/receitas.php';
    }
}

document.getElementById('adicionar-ingrediente').addEventListener('click', () => {
    document.getElementById('lista-ingredientes').appendChild(criarLinhaIngrediente());
});

document.getElementById('alterarreceita').addEventListener('click', () => {
    alterar();
});

async function alterar() {
    const id = document.getElementById('id').value;
    const titulo = document.getElementById('titulo').value.trim();
    const descricao = document.getElementById('descricao').value.trim();

    document.getElementById('error-titulo').textContent = '';
    document.getElementById('error-ingredientes').textContent = '';
    document.getElementById('error').textContent = '';

    if (!titulo) {
        document.getElementById('error-titulo').textContent = 'Por favor, preencha o título.';
        document.getElementById('titulo').focus();
        return;
    }

    const linhas = document.querySelectorAll('#lista-ingredientes .ingrediente');
    const ingredientes = [];

    linhas.forEach(linha => {
        const id_produto = linha.querySelector('.select-produto').value;
        const qtd = linha.querySelector('.input-qtd').value;
        const und_medida = linha.querySelector('.input-und').value.trim();
        ingredientes.push({ id_produto, qtd, und_medida });
    });

    if (ingredientes.length === 0) {
        document.getElementById('error-ingredientes').textContent = 'Adicione ao menos um ingrediente.';
        return;
    }

    const fd = new FormData();
    fd.append('titulo', titulo);
    fd.append('descricao', descricao);
    fd.append('ingredientes', JSON.stringify(ingredientes));

    const retorno = await fetch('/mykeeper/src/Controllers/receitas_alterar_back.php?id=' + id, {
        method: 'POST',
        body: fd
    });

    const resposta = await retorno.json();

    if (resposta.status == 'ok') {
        document.getElementById('error').textContent = 'SUCESSO! ' + resposta.mensagem;
        window.location.href = '/mykeeper/src/Views/receitas.php';
    } else {
        document.getElementById('error').textContent = 'ERRO! ' + resposta.mensagem;
    }
}