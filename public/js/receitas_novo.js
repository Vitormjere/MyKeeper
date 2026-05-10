function e(str) {
    const div = document.createElement('div');
    div.appendChild(document.createTextNode(str));
    return div.innerHTML;
}

function atualizarVisualSelect(select) {
    select.dataset.empty = select.value ? 'false' : 'true';
}

document.addEventListener('DOMContentLoaded', async () => {
    const response = await fetch('/mykeeper/config/check_session.php');
    const data = await response.json();

    if (!data.logado) {
        window.location.href = '/mykeeper/src/Views/usuario_login.php';
        return;
    }

    carregarProdutos();
});

async function carregarProdutos() {
    const retorno = await fetch('/mykeeper/src/Controllers/produto_get.php');
    const resposta = await retorno.json();

    if (resposta.status != 'ok') {
        document.getElementById('error-ingredientes').textContent = 'Erro ao carregar produtos.';
    }

    // guarda globalmente para usar ao clonar linhas
    window._produtos = resposta.status == 'ok' ? resposta.data : [];
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

document.getElementById('adicionar-ingrediente').addEventListener('click', () => {
    document.getElementById('lista-ingredientes').appendChild(criarLinhaIngrediente());
});

document.getElementById('addreceita').addEventListener('click', () => {
    novo();
});

async function novo() {
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

    const retorno = await fetch('/mykeeper/src/Controllers/receitas_novo_back.php', {
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