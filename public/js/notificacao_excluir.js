document.addEventListener('DOMContentLoaded', function() {
    var container = document.createElement('div');
    container.id = 'notificacao-container';
    document.body.appendChild(container);
});

var contador = 0;

function notificacaoExcluir(msg, tipo, aoConfirmar) {
    tipo = tipo || 'success';
    aoConfirmar = aoConfirmar || null;

    var temConfirm = tipo === 'confirm' && typeof aoConfirmar === 'function';
    var container = document.getElementById('notificacao-container');

    var notificacao = document.createElement('div');
    notificacao.className = 'notificacao ' + tipo;
    notificacao.id = 'notificacao-' + (++contador);

    var botoesHtml = '';
    if (temConfirm) {
        botoesHtml = `
        <div class="notificacao-actions">
            <button class="notificacao-btn notificacao-confirmar" data-action="sim">Sim, excluir</button>
            <button class="notificacao-btn" data-action="nao">Cancelar</button>
        </div>`;
    }

    notificacao.innerHTML = `
        <p class="notificacao-msg">${msg}</p>
        ${botoesHtml}
    `;

    container.classList.add('ativo');
    container.appendChild(notificacao);

    function fechar() {
        notificacao.classList.add('hide');
        setTimeout(function() {
            notificacao.remove();
            if (container.children.length === 0) {
                container.classList.remove('ativo');
            }
        }, 320);
    }

    if (temConfirm) {
        notificacao.querySelector('[data-action="sim"]').addEventListener('click', function() {
            fechar();
            aoConfirmar();
        });
        notificacao.querySelector('[data-action="nao"]').addEventListener('click', function() {
            fechar();
        });
    } else {
        setTimeout(function() {
            fechar();
        }, 3500);
    }
}