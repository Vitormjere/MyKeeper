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
            window.location.href = '/mykeeper/src/Views/usuario_login.php?motivo=expirado';
        } else {
            window.location.href = '/mykeeper/src/Views/usuario_login.php';
        }
        return;
    }

    const urlParams = new URLSearchParams(window.location.search);
    const id        = urlParams.get('id');
    const id_estoque = urlParams.get('id_estoque');

    document.getElementById('id').value         = id;
    document.getElementById('id_estoque').value = id_estoque;

    document.getElementById('btn-voltar').href =
        '/mykeeper/src/Views/estoque_itens.php?id_estoque=' + id_estoque;

    buscar(id);

    document.getElementById('alteraritem').addEventListener('click', () => {
        alterar();
    });
});

async function buscar(id) {
    const retorno  = await fetch('/mykeeper/src/Controllers/item_estoque_get.php?id=' + id);
    const resposta = await retorno.json();
    if (resposta.status == 'ok') {
        const item = resposta.data[0];
        document.getElementById('nome-produto').textContent = item.nome;
        document.getElementById('quantidade').value         = item.quantidade  ?? '';
        document.getElementById('marca').value              = item.marca        ?? '';
        if (item.data_validade) {
            document.getElementById('data_validade').value  = item.data_validade.split('T')[0];
        }
    } else {
        document.getElementById('error').textContent = 'ERRO: ' + resposta.mensagem;
    }
}

async function alterar() {
    const id          = document.getElementById('id').value;
    const id_estoque  = document.getElementById('id_estoque').value;
    const quantidade  = document.getElementById('quantidade').value;
    const data_validade = document.getElementById('data_validade').value;
    const marca       = document.getElementById('marca').value;

    const fd = new FormData();
    fd.append('quantidade',    quantidade);
    fd.append('data_validade', data_validade);
    fd.append('marca',         marca);

    const retorno = await fetch('/mykeeper/src/Controllers/item_estoque_alterar_back.php?id=' + id, {
        method: 'POST',
        body: fd
    });
    const resposta = await retorno.json();

    if (resposta.status == 'ok') {
        document.getElementById('error').style.color = '#00ffa3';
        document.getElementById('error').textContent = 'SUCESSO! ' + resposta.mensagem + '. Redirecionando...';
        setTimeout(() => {
            window.location.href = '/mykeeper/src/Views/estoque_itens.php?id_estoque=' + id_estoque;
        }, 1000);
    } else {
        document.getElementById('error').style.color = '#ff6b6b';
        document.getElementById('error').textContent = 'ERRO! ' + resposta.mensagem;
    }
}
