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

    buscar();
});

async function buscar() {
    const retorno = await fetch('/mykeeper/src/Controllers/receitas_get.php');
    const resposta = await retorno.json();

    if (resposta.status == 'ok' && resposta.data.length > 0) {
        renderizarCards(resposta.data);
    } else {
        document.getElementById('mensagem').textContent = 'Nenhuma receita cadastrada.';
    }
}

function renderizarCards(receitas) {
    const container = document.getElementById('item');
    container.innerHTML = '';

    receitas.forEach(receita => {
        const card = document.createElement('div');
        card.classList.add('receita-card');
        card.dataset.id = receita.id;
        card.dataset.carregado = 'false';

        card.innerHTML = `
            <div class="receita-header">
                <div class="receita-info">
                    <span class="receita-titulo">${e(receita.titulo)}</span>
                    <span class="receita-data">${formatarData(receita.data_geracao)}</span>
                </div>
                <div class="receita-meta">
                    <span class="badge ${receita.gerada_por_ia ? 'badge-ia' : 'badge-manual'}">
                        ${receita.gerada_por_ia ? 'IA' : 'Manual'}
                    </span>
                    <span class="receita-chevron">▼</span>
                </div>
            </div>
            <div class="receita-corpo">
                <h4>Descrição</h4>
                <p class="receita-descricao"></p>
                <ul class="receita-ingredientes"></ul>
                <div class="receita-acoes">
                    <button class="btn-editar" onclick="window.location.href='/mykeeper/receitas_alterar?id=${receita.id}'">Editar</button>
                    <button class="btn-excluir" onclick="excluir(${receita.id}, this)">Excluir</button>
                    <button class="btn-compartilhar" onclick="compartilhar(${receita.id})">Compartilhar</button>
                </div>
            </div>
        `;

        card.querySelector('.receita-header').addEventListener('click', () => {
            toggleCard(card);
        });

        container.appendChild(card);
    });
}

async function toggleCard(card) {
    const corpo = card.querySelector('.receita-corpo');
    const aberto = card.classList.contains('aberto');

    document.querySelectorAll('.receita-card.aberto').forEach(c => {
        if (c !== card) c.classList.remove('aberto');
    });

    if (aberto) {
        card.classList.remove('aberto');
        return;
    }

    if (card.dataset.carregado === 'false') {
        const id = card.dataset.id;
        const retorno = await fetch('/mykeeper/src/Controllers/receitas_get.php?id=' + id);
        const resposta = await retorno.json();

        if (resposta.status == 'ok') {
            const r = resposta.data;

            corpo.querySelector('.receita-descricao').textContent = r.descricao || 'Sem descrição.';

            const lista = corpo.querySelector('.receita-ingredientes');
            lista.innerHTML = '';

            const cores = {
                disponivel:   '#00ffa3',
                parcial:      '#f5a623',
                indisponivel: '#ff4d4d'
            };

            const legendaHtml = `
                <div class="ingredientes-legenda">
                    <span><span class="legenda-faixa" style="background:#00ffa3"></span> Em Estoque</span>
                    <span><span class="legenda-faixa" style="background:#f5a623"></span> Quantidade insuficiente</span>
                    <span><span class="legenda-faixa" style="background:#ff4d4d"></span> Indisponível</span>
                </div>
            `;

            lista.innerHTML = legendaHtml;

            r.ingredientes.forEach(ing => {
                const li = document.createElement('li');
                const cor = cores[ing.status_estoque] || '#888';

                li.classList.add('ingrediente-item');
                li.innerHTML = `
                    <span class="ingrediente-faixa" style="background: ${cor}"></span>
                    <span class="ingrediente-texto">${e(ing.nome)} — ${ing.qtd} ${e(ing.und_medida)}</span>
                `;
                lista.appendChild(li);
            });

            card.dataset.carregado = 'true';
        }
    }

    card.classList.add('aberto');
}

async function excluir(id) {
    notificacaoExcluir('Tem certeza que deseja excluir esta receita?', 'confirm', async function() {
        const retorno = await fetch('/mykeeper/src/Controllers/receitas_excluir.php?id=' + id);
        const resposta = await retorno.json();
        if (resposta.status == 'ok') {
            notificacaoExcluir(resposta.mensagem, 'success');
            setTimeout(function() { window.location.reload(); }, 1500);
        } else {
            notificacaoExcluir(resposta.mensagem, 'error');
        }
    });
}

function formatarData(data) {
    const [ano, mes, dia] = data.split('-');
    return `${dia}/${mes}/${ano}`;
}

document.getElementById('receita_nova').addEventListener('click', () => {
    window.location.href = '/mykeeper/receitas_novo';
});

async function compartilhar(id) {
    const retorno  = await fetch('/mykeeper/src/Controllers/receitas_compartilhar.php?id=' + id);
    const resposta = await retorno.json();

    if(resposta.status === 'ok'){
        await copiarTextoSistema(resposta.link, 'Link da receita copiado com sucesso.');
    } else {
        notificacaoSistema('ERRO! ' + resposta.mensagem, 'error');
    }
}
