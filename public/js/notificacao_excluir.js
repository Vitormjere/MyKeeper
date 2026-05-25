document.addEventListener('DOMContentLoaded', function() {
    criarContainerNotificacao();
});

var contador = 0;

function criarContainerNotificacao() {
    var container = document.getElementById('notificacao-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'notificacao-container';
        document.body.appendChild(container);
    }
    return container;
}

function notificacaoSistema(msg, tipo, opcoes) {
    tipo = tipo || 'success';
    opcoes = opcoes || {};

    var temConfirm = tipo === 'confirm' && typeof opcoes.aoConfirmar === 'function';
    var container = criarContainerNotificacao();
    var meta = obterMetaNotificacao(tipo, opcoes.variante);
    var mensagem = limparTextoNotificacao(msg);
    var titulo = opcoes.titulo || meta.titulo;

    var notificacao = document.createElement('div');
    notificacao.className = 'notificacao ' + tipo + (opcoes.variante ? ' ' + opcoes.variante : '');
    notificacao.id = 'notificacao-' + (++contador);

    var botoesHtml = '';
    if (temConfirm) {
        var textoConfirmar = opcoes.textoConfirmar || 'Confirmar';
        var textoCancelar = opcoes.textoCancelar || 'Cancelar';
        botoesHtml = `
        <div class="notificacao-actions">
            <button class="notificacao-btn notificacao-confirmar" data-action="sim">${textoConfirmar}</button>
            <button class="notificacao-btn" data-action="nao">${textoCancelar}</button>
        </div>`;
    }

    notificacao.innerHTML = `
        <div class="notificacao-header">
            <span class="notificacao-icone" aria-hidden="true">${meta.icone}</span>
            <div class="notificacao-textos">
                <strong class="notificacao-titulo">${escaparHtml(titulo)}</strong>
                <p class="notificacao-msg">${escaparHtml(mensagem)}</p>
            </div>
        </div>
        ${botoesHtml}
    `;

    container.classList.add('ativo');
    container.appendChild(notificacao);

    var fechado = false;
    var timerFechar = null;

    function fechar() {
        if (fechado) {
            return;
        }

        fechado = true;
        clearTimeout(timerFechar);
        container.removeEventListener('click', fecharAoClicarFora);
        notificacao.removeEventListener('click', fecharAoClicarNaNotificacao);
        notificacao.classList.add('hide');
        setTimeout(function() {
            notificacao.remove();
            if (container.children.length === 0) {
                container.classList.remove('ativo');
            }
        }, 320);
    }

    function fecharAoClicarFora(event) {
        if (event.target === container) {
            fechar();
        }
    }

    function fecharAoClicarNaNotificacao() {
        fechar();
    }

    if (temConfirm) {
        notificacao.querySelector('[data-action="sim"]').addEventListener('click', function() {
            fechar();
            opcoes.aoConfirmar();
        });
        notificacao.querySelector('[data-action="nao"]').addEventListener('click', function() {
            fechar();
        });
    } else {
        container.addEventListener('click', fecharAoClicarFora);
        notificacao.addEventListener('click', fecharAoClicarNaNotificacao);
        timerFechar = setTimeout(function() {
            fechar();
        }, opcoes.tempo || 3500);
    }
}

function obterMetaNotificacao(tipo, variante) {
    var metas = {
        success: {
            titulo: 'Sucesso',
            icone: 'OK'
        },
        error: {
            titulo: 'Erro',
            icone: '!'
        },
        warning: {
            titulo: 'Atenção',
            icone: '!'
        },
        info: {
            titulo: 'Informação',
            icone: 'i'
        },
        confirm: {
            titulo: 'Confirmação',
            icone: '?'
        }
    };

    if (variante === 'danger') {
        return {
            titulo: 'Excluir',
            icone: '!'
        };
    }

    return metas[tipo] || metas.info;
}

function limparTextoNotificacao(msg) {
    return String(msg || '')
        .replace(/^\s*SUCESSO!\s*/i, '')
        .replace(/^\s*ERRO!\s*/i, '')
        .replace(/^\s*ERRO:\s*/i, '')
        .trim();
}

function escaparHtml(valor) {
    var div = document.createElement('div');
    div.appendChild(document.createTextNode(String(valor || '')));
    return div.innerHTML;
}

function notificacaoExcluir(msg, tipo, aoConfirmar) {
    if (tipo === 'confirm') {
        notificacaoSistema(msg, tipo, {
            aoConfirmar: aoConfirmar,
            textoConfirmar: 'Sim, excluir',
            titulo: 'Excluir',
            variante: 'danger'
        });
        return;
    }

    notificacaoSistema(msg, tipo);
}

function confirmarSistema(msg, aoConfirmar, opcoes) {
    opcoes = opcoes || {};
    opcoes.aoConfirmar = aoConfirmar;
    notificacaoSistema(msg, 'confirm', opcoes);
}

async function copiarTextoSistema(texto, mensagemSucesso) {
    mensagemSucesso = mensagemSucesso || 'Link copiado para a área de transferência.';

    try {
        if (navigator.clipboard && window.isSecureContext) {
            await navigator.clipboard.writeText(texto);
        } else {
            var campo = document.createElement('textarea');
            campo.value = texto;
            campo.setAttribute('readonly', '');
            campo.style.position = 'fixed';
            campo.style.left = '-9999px';
            campo.style.top = '0';
            document.body.appendChild(campo);
            campo.focus();
            campo.select();

            var copiou = document.execCommand('copy');
            campo.remove();

            if (!copiou) {
                throw new Error('Falha ao copiar');
            }
        }

        notificacaoSistema(mensagemSucesso, 'success');
    } catch (erro) {
        notificacaoSistema('Não foi possível copiar automaticamente. Link:\n\n' + texto, 'info', { tempo: 9000 });
    }
}
