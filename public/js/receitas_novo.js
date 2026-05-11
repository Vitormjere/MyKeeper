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

    carregarCategorias();
});

async function carregarCategorias() {
    const retorno = await fetch('/mykeeper/src/Controllers/categoria_get.php');
    const resposta = await retorno.json();

    window._categorias = resposta.status == 'ok' ? resposta.data : [];
}

function popularSelectCategoria(select, valorSelecionado = '') {
    window._categorias.forEach(cat => {
        const option = document.createElement('option');
        option.value = cat.id;
        option.textContent = cat.nome;
        if (cat.id == valorSelecionado) option.selected = true;
        select.appendChild(option);
    });
    atualizarVisualSelect(select);
    select.addEventListener('change', () => atualizarVisualSelect(select));
}

function criarLinhaIngrediente(dados = {}) {
    const modelo = document.getElementById('modelo-ingrediente');
    const clone = modelo.cloneNode(true);
    clone.removeAttribute('id');
    clone.style.display = '';

    const selectCat = clone.querySelector('.select-categoria');
    const selectUnd = clone.querySelector('.select-und-medida');

    popularSelectCategoria(selectCat, dados.id_categoria ?? '');
    atualizarVisualSelect(selectUnd);
    selectUnd.addEventListener('change', () => atualizarVisualSelect(selectUnd));

    clone.querySelector('.input-nome').value = dados.nome ?? '';
    clone.querySelector('.input-qtd').value = dados.qtd ?? '';

    if (dados.und_medida) selectUnd.value = dados.und_medida;
    atualizarVisualSelect(selectUnd);

    clone.querySelector('.btn-remover').addEventListener('click', () => clone.remove());

    return clone;
}

document.getElementById('adicionar-ingrediente').addEventListener('click', () => {
    document.getElementById('lista-ingredientes').appendChild(criarLinhaIngrediente());
});

document.getElementById('addreceita').addEventListener('click', () => novo());

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
    if (linhas.length === 0) {
        document.getElementById('error-ingredientes').textContent = 'Adicione ao menos um ingrediente.';
        return;
    }

    let ingredienteInvalido = false;
    linhas.forEach((linha) => {
        const nome = linha.querySelector('.input-nome').value.trim();
        const categoria = linha.querySelector('.select-categoria').value;
        const undMedida = linha.querySelector('.select-und-medida').value;
        const qtd = linha.querySelector('.input-qtd').value;

        if (!nome || !categoria || !undMedida || !qtd || Number(qtd) <= 0) {
            ingredienteInvalido = true;
        }
    });

    if (ingredienteInvalido) {
        document.getElementById('error-ingredientes').textContent =
            'Preencha todos os campos de cada ingrediente (nome, categoria, unidade e quantidade).';
        return;
    }

    const fd = new FormData();
    fd.append('titulo', titulo);
    fd.append('descricao', descricao);

    linhas.forEach((linha, i) => {
        fd.append(`ingredientes[${i}][nome]`, linha.querySelector('.input-nome').value.trim());
        fd.append(`ingredientes[${i}][id_categoria]`, linha.querySelector('.select-categoria').value);
        fd.append(`ingredientes[${i}][und_medida]`, linha.querySelector('.select-und-medida').value);
        fd.append(`ingredientes[${i}][qtd]`, linha.querySelector('.input-qtd').value);

        const imagem = linha.querySelector('.input-imagem').files[0];
        if (imagem) fd.append(`ingredientes[${i}][imagem]`, imagem);
    });

    const retorno = await fetch('/mykeeper/src/Controllers/receitas_novo_back.php', {
        method: 'POST',
        body: fd
    });

    const resposta = await retorno.json();

    if (resposta.status == 'ok') {
        window.location.href = '/mykeeper/src/Views/receitas.php';
    } else {
        document.getElementById('error').textContent = 'ERRO! ' + resposta.mensagem;
    }
}