function e(str) {
    const div = document.createElement('div');
    div.appendChild(document.createTextNode(str));
    return div.innerHTML;
}

const STATUS_PERMITE_ENVIO = ['ticket_aberto', 'ticket_atualizado', 'ticket_respondido'];

let statusAtual = '';

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
    carregarChat();
});

async function carregarChat() {
    const retorno = await fetch('/mykeeper/src/Controllers/ticket_chat_get.php?id_ticket=' + ID_TICKET);
    const resposta = await retorno.json();

    if (resposta.status != 'ok') {
        document.getElementById('error-chat').textContent = resposta.mensagem;
        return;
    }

    document.getElementById('ticket-titulo').textContent = resposta.data.ticket.titulo;

    atualizarStatus(resposta.data.ticket.status_ticket);

    const container = document.getElementById('chat-messages');
    const vazio = document.getElementById('chat-vazio');
    const msgs = resposta.data.mensagens;

    if (msgs.length == 0) {
        vazio.style.display = 'flex';
    } else {
        vazio.style.display = 'none';
        for (let i = 0; i < msgs.length; i++) {
            container.appendChild(criarMensagem(msgs[i]));
        }
        scrollBottom();
    }
}

function atualizarStatus(status) {
    statusAtual = status;

    const badge = document.getElementById('ticket-status-badge');
    badge.textContent = labelStatus(status);
    badge.className = 'status-badge ' + classeStatus(status);

    const input = document.getElementById('input-mensagem');
    const btnEnviar = document.getElementById('btn-enviar');
    const bloqueado = document.getElementById('chat-bloqueado-msg');

    if (STATUS_PERMITE_ENVIO.includes(status)) {
        input.disabled = false;
        btnEnviar.disabled = false;
        bloqueado.style.display = 'none';
    } else {
        input.disabled = true;
        btnEnviar.disabled = true;
        bloqueado.style.display = 'block';
    }
}

function labelStatus(status) {
    if (status == 'ticket_aberto')     return 'Aberto';
    if (status == 'ticket_respondido') return 'Respondido';
    if (status == 'ticket_atualizado') return 'Atualizado';
    if (status == 'ticket_encerrado')  return 'Encerrado';
    return status;
}

function classeStatus(status) {
    if (status == 'ticket_aberto')     return 'status-aberto';
    if (status == 'ticket_respondido') return 'status-respondido';
    if (status == 'ticket_atualizado') return 'status-atualizado';
    if (status == 'ticket_encerrado')  return 'status-encerrado';
    return '';
}

function criarMensagem(msg) {
    let enviada = false;
    if (msg.tipo_remetente == 'usuario' && TIPO_SESSAO == 0 && msg.id_remetente == ID_USUARIO) {
        enviada = true;
    }
    if (msg.tipo_remetente == 'suporte' && TIPO_SESSAO == 1 && msg.id_remetente == ID_USUARIO) {
        enviada = true;
    }

    const div = document.createElement('div');
    div.className = 'msg ' + (enviada ? 'enviada' : 'recebida');

    const autor = document.createElement('p');
    autor.className = 'msg-autor';
    if (msg.nome_remetente) {
        autor.textContent = msg.nome_remetente;
    } else if (msg.tipo_remetente == 'suporte') {
        autor.textContent = 'Suporte';
    } else {
        autor.textContent = 'Usuário';
    }

    const balao = document.createElement('div');
    balao.className = 'msg-balao';
    balao.textContent = msg.mensagem;

    const hora = document.createElement('p');
    hora.className = 'msg-hora';
    hora.textContent = formatarHora(msg.data_envio);

    div.appendChild(autor);
    div.appendChild(balao);
    div.appendChild(hora);

    return div;
}

function formatarHora(datetimeStr) {
    const d = new Date(datetimeStr);
    return d.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });
}

function scrollBottom() {
    const container = document.getElementById('chat-messages');
    container.scrollTop = container.scrollHeight;
}

document.getElementById('btn-enviar').addEventListener('click', () => {
    enviarMensagem();
});

document.getElementById('input-mensagem').addEventListener('keydown', function(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        enviarMensagem();
    }
});

document.getElementById('input-mensagem').addEventListener('input', function() {
    this.style.height = 'auto';
    this.style.height = Math.min(this.scrollHeight, 120) + 'px';
});

async function enviarMensagem() {
    const input = document.getElementById('input-mensagem');
    const texto = input.value.trim();
    const erro = document.getElementById('error-chat');
    erro.textContent = '';

    if (!texto) {
        erro.textContent = 'Digite uma mensagem antes de enviar.';
        return;
    }

    if (!STATUS_PERMITE_ENVIO.includes(statusAtual)) {
        erro.textContent = 'Este ticket não permite novas mensagens.';
        return;
    }

    let tipoRemetente = 'usuario';
    if (TIPO_SESSAO == 1) {
        tipoRemetente = 'suporte';
    }

    const fd = new FormData();
    fd.append('id_ticket', ID_TICKET);
    fd.append('mensagem', texto);
    fd.append('tipo_remetente', tipoRemetente);

    const btnEnviar = document.getElementById('btn-enviar');
    btnEnviar.disabled = true;

    const retorno = await fetch('/mykeeper/src/Controllers/ticket_mensagem_post.php', {
        method: 'POST',
        body: fd
    });
    const resposta = await retorno.json();

    btnEnviar.disabled = false;

    if (resposta.status == 'ok') {
        input.value = '';
        input.style.height = 'auto';

        const container = document.getElementById('chat-messages');
        document.getElementById('chat-vazio').style.display = 'none';
        container.appendChild(criarMensagem(resposta.data));
        scrollBottom();

        if (resposta.data.status_ticket) {
            atualizarStatus(resposta.data.status_ticket);
        }
    } else {
        erro.textContent = resposta.mensagem;
    }
}

document.getElementById('btn-voltar').addEventListener('click', () => {
    if (TIPO_SESSAO == 0) {
        window.location.href = '/mykeeper/ticket_usuario';
    } else {
        window.location.href = '/mykeeper/tickets_suporte';
    }
});