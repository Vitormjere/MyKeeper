const rec = new (window.SpeechRecognition || window.webkitSpeechRecognition)();

rec.lang = 'pt-BR';
rec.continuous = true;
rec.interimResults = true;

var ouvindo = false;

const mic = `
<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#0b0e11" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"/>
    <path d="M19 10v2a7 7 0 0 1-14 0v-2"/>
    <line x1="12" y1="19" x2="12" y2="23"/>
    <line x1="8" y1="23" x2="16" y2="23"/>
</svg>
`;

var toastBox = document.createElement('div');

toastBox.style.cssText = `
    position:fixed;
    top:20px;
    right:20px;
    z-index:9999;
`;

document.body.appendChild(toastBox);

function toast(msg, erro = false){
    var div = document.createElement('div');
    div.style.cssText = `
        background:${erro ? '#E24B4A' : '#1D9E75'};
        color:#fff;
        padding:12px 16px;
        border-radius:6px;
        margin-bottom:10px;
        font-size:14px;
        font-weight:600;
    `;

    div.innerText = msg;
    toastBox.appendChild(div);
    setTimeout(function(){
        div.style.opacity = '0';
        setTimeout(function(){
            div.remove();
        }, 200);
    }, 1800);
}

document.getElementById('btnVoz').addEventListener('click', ()=>{

    if(ouvindo){
        rec.stop();
        ouvindo = false;
        document.getElementById('btnVoz').innerHTML = mic;
        return;
    }

    rec.start();
    ouvindo = true;
    document.getElementById('btnVoz').innerHTML = '✕';
});

//entende
rec.onresult = async (e)=>{
    var i = e.results.length - 1;
    if(!e.results[i].isFinal){
        return;
    }
    var texto = e.results[i][0].transcript.trim().toLowerCase();
    if(texto == ''){
        return;
    }

    texto = texto.charAt(0).toUpperCase() + texto.slice(1);

    var fd = new FormData();

    fd.append('nome_produto', texto);
    fd.append('id_categoria', '');
    fd.append('und_medida_produto', '');

    try{

        const retorno = await fetch('/mykeeper/src/Controllers/produto_novo_back.php', {
            method:'POST',
            body:fd
        });
        const resposta = await retorno.json();
        if(resposta.status == 'ok'){
            toast(texto + ' adicionado!');
        }
        else{
            toast('Erro ao adicionar ' + texto, true);
        }

    }
    catch(err){
        toast('Erro no microfone', true);
    }
};

rec.onerror = (evento)=>{
    ouvindo = false;
    document.getElementById('btnVoz').innerHTML = mic;
    toast('Erro: ' + evento.error, true);
};

rec.onend = ()=>{

    if(ouvindo){
        try{
            rec.start();
        }
        catch(e){}
    }
};
