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

const urlParams = new URLSearchParams(window.location.search);
const id_lista_compra = urlParams.get('id');

if (!id_lista_compra) {
    window.location.href = '/mykeeper/src/Views/compras.php';
}

document.addEventListener('DOMContentLoaded', async () => {

    const response = await fetch('/mykeeper/config/check_session.php');
    const data = await response.json();

    if (!data.logado) {
        if (data.expirado) {
            window.location.href = '/mykeeper/src/Views/usuario_login.php?motivo=expirado';
        } else {
            window.location.href = '/mykeeper/src/Views/usuario_login.php';
        }
        return;
    }

    buscarEstoques();
    buscarItens();

    document.getElementById('btnConfirmar').addEventListener('click', confirmar);
});

async function buscarEstoques() {
    const retorno = await fetch('/mykeeper/src/Controllers/estoque_get.php');
    const resposta = await retorno.json();

    const select = document.getElementById('selectEstoque');
    select.innerHTML = '';

    if (resposta.status == 'ok') {
        for (var i = 0; i < resposta.data.length; i++) {
            const option = document.createElement('option');

            option.value = resposta.data[i].id;

            option.textContent = resposta.data[i].nome_estoque;

            select.appendChild(option);
        }
    } else {
        select.innerHTML = '<option value="">Nenhum estoque encontrado</option>';
    }
}

async function buscarItens() {
    const retorno = await fetch('/mykeeper/src/Controllers/compras_itens_get.php?id_lista_compra=' + id_lista_compra);
    const resposta = await retorno.json();

    if (resposta.status == 'ok') {
        preencherCards(resposta.data);
    } else {
        document.getElementById('mensagem').textContent = 'Nenhum item encontrado nesta lista.';
        document.getElementById('btnConfirmar').style.display = 'none';
    }
}

function preencherCards(tabela) {
    var html = '';

    for (var i = 0; i < tabela.length; i++) {

        html += `
        <div class="card" data-id-produto="${tabela[i].id_produto}" data-quantidade="${tabela[i].quantidade}">

            <div class="card-nome">${e(tabela[i].nome)}</div>
            <div class="card-data">${respostaOuTracos(tabela[i].quantidade)} ${respostaOuTracos(tabela[i].und_medida)}</div>
            <div class="card-inputs">
                <input type="text" placeholder="Marca (opcional)" class="input-marca">
                <input type="date" class="input-validade">
            </div>

        </div>`;
    }
    document.getElementById('item').innerHTML = html;
}

async function confirmar() {
    const id_estoque = document.getElementById('selectEstoque').value;

    if (!id_estoque) {
        notificacaoSistema('Selecione um estoque antes de confirmar.', 'warning');
        return;
    }

    const cards = document.querySelectorAll('#item .card');

    if (cards.length === 0) {
        notificacaoSistema('Nenhum item para adicionar.', 'warning');
        return;
    }

    confirmarSistema('Confirmar? Os itens serão adicionados ao estoque e a lista será concluída.', finalizarCompra, {
        textoConfirmar: 'Sim, confirmar'
    });
}

async function finalizarCompra() {
    const id_estoque = document.getElementById('selectEstoque').value;
    const cards = document.querySelectorAll('#item .card');
    const itens = [];
    for (var i = 0; i < cards.length; i++) {
        itens.push({
            id_produto:  cards[i].dataset.idProduto,
            quantidade:  cards[i].dataset.quantidade,
            marca:       cards[i].querySelector('.input-marca').value || null,
            data_validade: cards[i].querySelector('.input-validade').value || null
        });
    }

    const fd = new FormData();
    fd.append('id_lista_compra', id_lista_compra);
    fd.append('id_estoque', id_estoque);
    fd.append('itens', JSON.stringify(itens));

    const retorno = await fetch('/mykeeper/src/Controllers/compras_concluir_salvar.php', {
        method: 'POST',
        body: fd
    });
    
    const resposta = await retorno.json();

    if (resposta.status == 'ok') {
        notificacaoSistema('SUCESSO! ' + resposta.mensagem, 'success');
        setTimeout(function() {
            window.location.href = '/mykeeper/src/Views/compras.php';
        }, 1200);
    } else {
        notificacaoSistema('ERRO! ' + resposta.mensagem, 'error');
    }
}
