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
    buscar();
});

async function buscar() {

    const retorno = await fetch('/mykeeper/src/Controllers/compras_get.php');
    const resposta = await retorno.json();

    if (resposta.status == 'ok') {
        preencherTabela(resposta.data);
    } else {
        document.getElementById('mensagem').textContent = 'Não há listas de compras cadastradas.';
    }
}

function labelStatus(status) {

    const map = { 'aberta': 'Aberta', 'concluida': 'Concluída', 'arquivada': 'Arquivada' };
    return map[status] || status;

}

function preencherTabela(tabela) {

    var html = '';

    for (var i = 0; i < tabela.length; i++) {

        const status = tabela[i].status_compra || 'aberta';

        html += `
        <div class="card" style="cursor:pointer;" onclick="window.location.href='compras_itens.php?id_lista_compra=${tabela[i].id}'">
            <div class="card-status-bar ${e(status)}"></div>
            <div class="card-body">

                <div class="card-header">

                    <div>
                        <p class="card-nome">${e(tabela[i].titulo)}</p>
                        <p class="card-data">Criado em: ${new Date(tabela[i].data_criacao).toLocaleDateString('pt-BR')}</p>
                    </div>

                    <div class="badge-status ${e(status)}">
                        <span class="badge-dot ${e(status)}"></span>
                        ${labelStatus(status)}
                    </div>

                </div>

                ${status === 'aberta' ? `
                <div class="card-botoes-status">
                    <button class="btn-concluir" onclick="event.stopPropagation(); window.location.href='compras_concluir.php?id=${tabela[i].id}'">
                        ✓ Concluída
                    </button>
                    <button class="btn-arquivar" onclick="event.stopPropagation(); alterarStatus(${tabela[i].id}, 'arquivada')">
                        ⊘ Arquivar
                    </button>
                </div>` : ''}

                <div class="card-botoes">

                    <button class="btn-editar" onclick="event.stopPropagation()">
                        <a href="compras_alterar.php?id=${tabela[i].id}">Editar</a>
                    </button>

                    <button class="btn-excluir" onclick="event.stopPropagation(); excluir(${tabela[i].id})">
                        Excluir
                    </button>

                    <button class="btn-compartilhar" onclick="event.stopPropagation(); compartilhar(${tabela[i].id})">
                        Compartilhar
                    </button>

                </div>
                
            </div>
        </div>`;
    }

    document.getElementById('item').innerHTML = html;
}

async function excluir(id) {
    notificacaoExcluir('Tem certeza que deseja excluir esta lista de compras?', 'confirm', async function() {
        const retorno = await fetch('/mykeeper/src/Controllers/compras_excluir.php?id=' + id);
        const resposta = await retorno.json();
        if (resposta.status == 'ok') {
            notificacaoExcluir(resposta.mensagem, 'success');
            setTimeout(function() { window.location.reload(); }, 1500);
        } else {
            notificacaoExcluir(resposta.mensagem, 'error');
        }
    });
}

document.getElementById('criarCompras').addEventListener('click', () => {
    window.location.href = '/mykeeper/src/Views/compras_novo.php';
});

async function compartilhar(id) {
    const retorno  = await fetch('/mykeeper/src/Controllers/lista_compras_compartilhar.php?id=' + id);
    const resposta = await retorno.json();

    if(resposta.status === 'ok'){
        await copiarTextoSistema(resposta.link, 'Link da lista copiado com sucesso.');
    } else {
        notificacaoSistema('ERRO! ' + resposta.mensagem, 'error');
    }
}

async function alterarStatus(id, novoStatus) {

    const labels = { 'concluida': 'concluir', 'arquivada': 'arquivar' };

    confirmarSistema(`Tem certeza que deseja ${labels[novoStatus]} esta lista?`, async function() {
        await salvarStatus(id, novoStatus);
    }, {
        textoConfirmar: 'Sim, continuar'
    });
}

async function salvarStatus(id, novoStatus) {
    const fd = new FormData();
    fd.append('id', id);
    fd.append('status', novoStatus);

    const retorno = await fetch('/mykeeper/src/Controllers/compras_status.php', {
        method: 'POST',
        body: fd
    });

    const resposta = await retorno.json();

    if (resposta.status == 'ok') {
        notificacaoSistema('SUCESSO! ' + resposta.mensagem, 'success');
    } else {
        notificacaoSistema('ERRO! ' + resposta.mensagem, 'error');
    }
    window.location.reload();
}
