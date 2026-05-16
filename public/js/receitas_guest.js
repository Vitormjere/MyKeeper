fetch(`/mykeeper/src/Controllers/receitas_guest.php?token=${token}`)
    .then(r => r.json())
    .then(res => {
        if(res.status !== 'ok'){
            document.getElementById('conteudo').innerHTML = '<p class="vazio">Receita não encontrada.</p>';
            return;
        }

        const r = res.data;

        let ingredientesHtml = '';
        if(r.ingredientes.length === 0){
            ingredientesHtml = '<p class="vazio">Nenhum ingrediente cadastrado.</p>';
        } else {
            ingredientesHtml = `
                <table>
                    <thead>
                        <tr>
                            <th>Ingrediente</th>
                            <th>Quantidade</th>
                            <th>Unidade</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${r.ingredientes.map(i => `
                            <tr>
                                <td>${i.nome}</td>
                                <td>${i.qtd}</td>
                                <td>${i.und_medida}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            `;
        }

        const [ano, mes, dia] = r.data_geracao.split('-');
        const dataFormatada = `${dia}/${mes}/${ano}`;

        document.getElementById('conteudo').innerHTML = `
            <div class="badges">
                <span class="badge ${r.gerada_por_ia ? 'badge-ia' : 'badge-manual'}">
                    ${r.gerada_por_ia ? 'IA' : 'Manual'}
                </span>
                <span class="badge">Criado em: ${dataFormatada}</span>
            </div>
            <h1>${r.titulo}</h1>
            <p class="descricao">${r.descricao || 'Sem descrição.'}</p>
            <h3>Ingredientes</h3>
            ${ingredientesHtml}
        `;
    });