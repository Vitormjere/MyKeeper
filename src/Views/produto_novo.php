<?php
include_once(__DIR__ . '/../../config/valida_sessao.php');
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Produto</title>
    <link rel="stylesheet" href="/mykeeper/public/css/produto_novo.css">
</head>

<body>
    <section>
        <a href="/mykeeper/src/Views/produto.php">
            <img src="/mykeeper/public/assets/perto.png" alt="x.png" style="position:fixed; top:12px; left:12px; width:32px; height:32px; object-fit:contain;">
        </a>
        <form>
            <div style="display: flex; align-items: center; gap: 12px; justify-content: center;">
                <h2>Novo Produto</h2>
                <button type="button" id="btnVoz" title="Adicionar por voz">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#0b0e11" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"/>
                        <path d="M19 10v2a7 7 0 0 1-14 0v-2"/>
                        <line x1="12" y1="19" x2="12" y2="23"/>
                        <line x1="8" y1="23" x2="16" y2="23"/>
                    </svg>
                </button>
                <!-- CASO QUEIRA USAR O EMOJI, APAGA O DE CIMA E USA O DE BAIXO -->
                <!-- <button type="button" id="btnVoz" title="Adicionar por voz" >🎙️</button> -->
            </div>

            <div>
                <label for="nome_produto">Nome</label>
                <input type="text" name="nome_produto" id="nome_produto">
                <p id="error-nome"></p>
            </div>

            <div>
                <label for="quantidade_produto">Quantidade</label>
                <input type="number" name="quantidade_produto" id="quantidade_produto" min="0" step="0.01">
                <p id="error-quantidade"></p>
            </div>

            <div>
                <label for="categoria_produto">Categoria</label>
                <select name="categoria_produto" id="categoria_produto">
                    <option value="" data-placeholder="true">Escolha a categoria do produto</option>
                </select>
                <p id="error-categoria"></p>
            </div>
            
            <div>
                <label for="und_medida_produto">Unidade de medida</label>
                <select name="und_medida_produto" id="und_medida_produto">
                    <option value="" data-placeholder="true">Escolha como esse item sera medido</option>
                    <option value="kg">Quilo (Kg)</option>
                    <option value="gramas">Gramas (g)</option>
                    <option value="l">Litro (L)</option>
                    <option value="ml">Mililitro (mL)</option>
                </select>
                <p id="error-unidade"></p>
            </div>

            <div>
                <label for="icone_produto">Ícone do produto</label>
                <input type="file" name="icone_produto" id="icone_produto" accept="image/png, image/jpeg, image/jpg">
            </div>

            <div>
                <img src="" id="preview" style="display:none; width:100px; height:100px;">
            </div>
            <div>
                <p id="error"></p>
            </div>
            <button type="button" id="addproduto">Adicionar</button>
        </form>
    </section>

    <script src="/mykeeper/public/js/notificacao_excluir.js"></script>
    <script src="/mykeeper/public/js/voz.js"></script>
    <script src="/mykeeper/public/js/produto_novo.js"></script>
</body>

</html>
