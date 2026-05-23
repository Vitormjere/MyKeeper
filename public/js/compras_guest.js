fetch(`/mykeeper/src/Controllers/item_lista_compras_compartilhar.php?token=${token}`)
    .then(r => r.json())
    .then(res => {
        if(res.status !== 'ok'){
            document.getElementById('conteudo').innerHTML = '<p class="vazio">Lista não encontrada.</p>';
            return;
        }

        const lista = res.lista;
        const itens = res.data;

        let html = `
            <h1>${lista.titulo}</h1>
            <div class="badges">
                <span class="badge ${lista.status_compra}">${lista.status_compra}</span>
                <span class="badge">Criado em: ${new Date(lista.data_criacao).toLocaleDateString('pt-BR')}</span>
            </div>
        `;

        if(itens.length === 0){
            html += '<p class="vazio">Nenhum item nesta lista.</p>';
        } else {
            html += `
                <div class="tabela-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Produto</th>
                                <th>Categoria</th>
                                <th>Quantidade</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${itens.map(i => `
                                <tr>
                                    <td>${i.nome}</td>
                                    <td>${i.nome_categoria}</td>
                                    <td>${i.quantidade ?? '—'}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            `;
        }

        document.getElementById('conteudo').innerHTML = html;
    });