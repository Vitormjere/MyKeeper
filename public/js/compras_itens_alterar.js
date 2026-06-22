function e(str) {
    const div = document.createElement('div');
    div.appendChild(document.createTextNode(str));
    return div.innerHTML;
}

document.addEventListener('DOMContentLoaded', async () => {
    const response = await fetch('/mykeeper/config/check_session.php');
    const data = await response.json();
    if (!data.logado) {
        if (data.expirado) {
            window.location.href = '/mykeeper/usuario_login?motivo=expirado';
        } else {
            window.location.href = '/mykeeper/usuario_login';
        }
        return;
    }

    const urlParams = new URLSearchParams(window.location.search);
    const id_lista_compra = urlParams.get('id_lista_compra');
    const id_produto     = urlParams.get('id_produto');

    if (!id_lista_compra || !id_produto) {
        window.location.href = '/mykeeper/compras';
        return;
    }

    document.getElementById('id_lista_compra').value = id_lista_compra;
    document.getElementById('id_produto').value = id_produto;


    document.getElementById('btn-voltar').href =
        '/mykeeper/compras_itens?id_lista_compra=' + id_lista_compra;

    buscar(id_lista_compra, id_produto);

    document.getElementById('alteraritem').addEventListener('click', () => {
        alterar();
    });
});

async function buscar(id_lista_compra, id_produto) {
    const retorno  = await fetch('/mykeeper/src/Controllers/compras_itens_get.php?id_lista_compra=' + id_lista_compra + '&id_produto=' + id_produto);
    const resposta = await retorno.json();
    if (resposta.status == 'ok') {
        const item = resposta.data[0];
        document.getElementById('nome-produto').textContent = item.nome;
        document.getElementById('quantidade').value         = item.quantidade  ?? '';
    } else {
        document.getElementById('error').textContent = 'ERRO: ' + resposta.mensagem;
    }
}

async function alterar() {
    const id_lista_compra = document.getElementById('id_lista_compra').value;
    const id_produto     = document.getElementById('id_produto').value;
    const quantidade  = document.getElementById('quantidade').value;

    const fd = new FormData();
    fd.append('quantidade',    quantidade);

    const retorno = await fetch('/mykeeper/src/Controllers/compras_itens_alterar_back.php?id_lista_compra=' + id_lista_compra + '&id_produto=' + id_produto, {
        method: 'POST',
        body: fd
    });
    const resposta = await retorno.json();

    if (resposta.status == 'ok') {
        document.getElementById('error').style.color = '#00ffa3';
        document.getElementById('error').textContent = 'SUCESSO! ' + resposta.mensagem + '. Redirecionando...';
        setTimeout(() => {
            window.location.href = '/mykeeper/compras_itens?id_lista_compra=' + id_lista_compra;
        }, 1000);
    } else {
        document.getElementById('error').style.color = '#ff6b6b';
        document.getElementById('error').textContent = 'ERRO! ' + resposta.mensagem;
    }
}
