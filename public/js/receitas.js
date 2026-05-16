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
                <h4> Descrição </h4>
                <p class="receita-descricao"></p>
                <ul class="receita-ingredientes"></ul>
                <div class="receita-acoes">
                    <button class="btn-editar" onclick="window.location.href='/mykeeper/src/Views/receitas_alterar.php?id=${receita.id}'">Editar</button>
                    <button class="btn-excluir" onclick="excluir(${receita.id}, this)">Excluir</button>
                    <button class="btn-compartilhar" onclick="compartilhar(${receita.id})">Compartilhar</button>
                </div>
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

    // fecha todos os outros
    document.querySelectorAll('.receita-card.aberto').forEach(c => {
        if (c !== card) c.classList.remove('aberto');
    });

    if (aberto) {
        card.classList.remove('aberto');
        return;
    }

    // busca detalhes só na primeira vez
    if (card.dataset.carregado === 'false') {
        const id = card.dataset.id;
        const retorno = await fetch('/mykeeper/src/Controllers/receitas_get.php?id=' + id);
        const resposta = await retorno.json();

        if (resposta.status == 'ok') {
            const r = resposta.data;

            corpo.querySelector('.receita-descricao').textContent = r.descricao || 'Sem descrição.';

            const lista = corpo.querySelector('.receita-ingredientes');
            lista.innerHTML = '';

            r.ingredientes.forEach(ing => {
                const li = document.createElement('li');
                li.textContent = `${e(ing.nome)} — ${ing.qtd} ${e(ing.und_medida)}`;
                lista.appendChild(li);
            });

            card.dataset.carregado = 'true';
        }
    }

    card.classList.add('aberto');
}

async function excluir(id, btn) {
    if (!confirm('Tem certeza que deseja excluir esta receita?')) return;

    const retorno = await fetch('/mykeeper/src/Controllers/receitas_excluir.php?id=' + id);
    const resposta = await retorno.json();

    if (resposta.status == 'ok') {
        btn.closest('.receita-card').remove();
        alert('SUCESSO! ' + resposta.mensagem);
    } else {
        alert('ERRO! ' + resposta.mensagem);
    }
}

function formatarData(data) {
    const [ano, mes, dia] = data.split('-');
    return `${dia}/${mes}/${ano}`;
}

document.getElementById('receita_nova').addEventListener('click', () => {
    window.location.href = '/mykeeper/src/Views/receitas_novo.php';
});

async function compartilhar(id) {
    const retorno  = await fetch('/mykeeper/src/Controllers/receitas_compartilhar.php?id=' + id);
    const resposta = await retorno.json();

    if(resposta.status === 'ok'){
        navigator.clipboard.writeText(resposta.link)
            .then(() => alert('Link copiado!\n\n' + resposta.link))
            .catch(() => prompt('Copie o link abaixo:', resposta.link));
    } else {
        alert('ERRO! ' + resposta.mensagem);
    }
}