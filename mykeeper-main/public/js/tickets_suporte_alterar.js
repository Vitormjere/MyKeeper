function e(str) {
    const div = document.createElement('div');
    div.appendChild(document.createTextNode(str));
    return div.innerHTML;
}

document.addEventListener('DOMContentLoaded', async ()=>{
    // 1. Primeiro verifica se está logado
    const response = await fetch('../../config/check_session.php');
    const data = await response.json();
    
    if (!data.logado) {
        window.location.href = 'usuario_login.php';
        return; // para a execução aqui
    }

    const urlParams = new URLSearchParams(window.location.search);
    const id = urlParams.get('id');

    document.getElementById('ticketId').value = id;

    buscar(id);
});


async function buscar(id){
    const retorno = await fetch('../Controllers/tickets_suporte_get.php?id='+id);
    const resposta = await retorno.json();

    if(resposta.status == 'ok'){
        var item = resposta.data;

        document.getElementById('usuario').textContent    = e(item.usuario_nome);
        document.getElementById('titulo').textContent     = e(item.titulo);
        document.getElementById('descricao').textContent  = e(item.descricao);
        document.getElementById('data_ticket').textContent = e(new Date(item.data_ticket).toLocaleString());
        document.getElementById('resposta_ticket').value  = e(item.resposta_ticket);
        document.getElementById('status_ticket').value    = e(item.status_ticket);

    }else{
        alert("ERRO: "+resposta.mensagem)
        window.location.href = 'tickets_suporte.php';
    }
}

document.getElementById('alterarTicket').addEventListener('click', ()=>{
    alterar();
});

async function alterar(){
    let resposta_ticket = document.getElementById('resposta_ticket').value;
    let status_ticket = document.getElementById('status_ticket').value;
    let id = document.getElementById('ticketId').value;

    const fd = new FormData();

    fd.append('resposta_ticket', resposta_ticket);
    fd.append('status_ticket', status_ticket);

    const retorno = await fetch('../Controllers/tickets_suporte_alterar_back.php?id='+id, {
        method: 'POST',
        body: fd
    });

    const resposta = await retorno.json();

    if(resposta.status == 'ok'){
        alert('SUCESSO! ' + resposta.mensagem);
        window.location.href = 'tickets_suporte.php';
    }else{
        alert('ERRO! ' + resposta.mensagem);
    }
}
