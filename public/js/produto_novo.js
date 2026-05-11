document.getElementById('icone_produto').addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            const preview = document.getElementById('preview');
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
});

function atualizarVisualSelect(select) {
    select.dataset.empty = select.value ? 'false' : 'true';
}

document.addEventListener('DOMContentLoaded', async () => {
    document.querySelectorAll('select').forEach((select) => {
        atualizarVisualSelect(select);
        select.addEventListener('change', () => atualizarVisualSelect(select));
    });

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

    if (resposta.status == 'ok') {
        const select = document.getElementById('categoria_produto');
        resposta.data.forEach(categoria => {
            const option = document.createElement('option');
            option.value = categoria.id;
            option.textContent = categoria.nome;
            select.appendChild(option);
        });

        atualizarVisualSelect(select);
    } else {
        document.getElementById('error-categoria').textContent = 'Erro ao carregar categorias';
    }
}

document.getElementById('addproduto').addEventListener('click', () => {
    novo();
});

async function novo() {
    const nome_produto = document.getElementById('nome_produto').value.trim();
    const categoria_produto = document.getElementById('categoria_produto').value;
    const und_medida_produto = document.getElementById('und_medida_produto').value.trim();
    const icone_produto = document.getElementById('icone_produto').files[0];

    if (!nome_produto) {
        document.getElementById('error-nome').textContent = 'Por favor, preencha o nome do produto.';
        document.getElementById('nome_produto').focus();
        return;
    }
    if (!und_medida_produto) {
        document.getElementById('error-unidade').textContent = 'Por favor, preencha a unidade de medida.';
        document.getElementById('und_medida_produto').focus();
        return;
    }

    if (!categoria_produto){
        document.getElementById('error-categoria').textContent = 'Por favor, selecione uma categoria.';
        document.getElementById('categoria_produto').focus();
        return;
    }
    

    const fd = new FormData();
    fd.append('nome_produto', nome_produto);
    fd.append('id_categoria', categoria_produto);
    fd.append('und_medida_produto', und_medida_produto);

    if (icone_produto) {
        fd.append('icone_produto', icone_produto);
    }

    const retorno = await fetch('/mykeeper/src/Controllers/produto_novo_back.php', {
        method: 'POST',
        body: fd
    });

    const resposta = await retorno.json();

    if (resposta.status == 'ok') {
        document.getElementById('error').textContent = 'SUCESSO! ' + resposta.mensagem + '. Redirecionando...';
        setTimeout(() => {
            window.location.href = '/mykeeper/src/Views/produto.php';
        }, 1000);
    } else {
        document.getElementById('error').textContent = 'ERRO! ' + resposta.mensagem;
    }
}
