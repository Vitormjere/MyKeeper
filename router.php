<?php
$pagina = $_GET['p'] ?? '';

$paginasPermitidas = [
    'home', 'produto', 'produto_novo', 'produto_alterar',
    'estoque', 'estoque_itens', 'estoque_adicionar', 'estoque_alterar', 'item_estoque_adicionar', 'item_estoque_alterar',
    'categoria', 'categoria_novo', 'categoria_alterar',
    'compras', 'compras_itens', 'compras_novo', 'compras_alterar', 'compras_concluir',
    'compras_itens_adicionar', 'compras_itens_alterar', 'compras_guest',
    'receitas', 'receitas_novo', 'receitas_alterar', 'receitas_guest',
    'perfil_usuario', 'perfil_alterar',
    'ticket_usuario', 'ticket_usuario_novo', 'ticket_usuario_alterar', 'chat_ticket',
    'tickets_suporte', 'tickets_suporte_alterar',
    'suporte', 'suporte_novo', 'suporte_alterar',
    'usuario_login', 'usuario_cadastro',
    'admin_login', 'admin_home', 'senha_alterar'
];

if (!in_array($pagina, $paginasPermitidas)) {
    http_response_code(404);
    echo "Página não encontrada.";
    exit;
}

$arquivo = __DIR__ . '/src/Views/' . $pagina . '.php';

if (file_exists($arquivo)) {
    include $arquivo;
} else {
    http_response_code(404);
    echo "Página não encontrada.";
}