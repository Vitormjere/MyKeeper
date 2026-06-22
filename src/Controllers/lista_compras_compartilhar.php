<?php
include_once(__DIR__ . '/../../config/headers.php');
include_once(__DIR__ . '/../../config/conexao.php');
if(session_status() === PHP_SESSION_NONE){
    session_start();
}

$id_usuario = $_SESSION['usuario']['id'];
$id_lista   = $_GET['id'] ?? null;

if(!$id_lista){
    echo json_encode(['status' => 'nok', 'mensagem' => 'ID não informado']);
    exit;
}

// Verifica se já tem token gerado
$stmt = $conexao->prepare("SELECT link_compart FROM lista_compras WHERE id = ? AND id_usuario = ?");
$stmt->bind_param('ii', $id_lista, $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result()->fetch_assoc();

if(!$resultado){
    echo json_encode(['status' => 'nok', 'mensagem' => 'Lista não encontrada']);
    exit;
}

if(!empty($resultado['link_compart'])){
    $token = $resultado['link_compart'];
} else {
    $token = bin2hex(random_bytes(32));
    $stmt  = $conexao->prepare("UPDATE lista_compras SET link_compart = ? WHERE id = ? AND id_usuario = ?");
    $stmt->bind_param('sii', $token, $id_lista, $id_usuario);
    $stmt->execute();
}

// Lê a URL base do .env
$config = parse_ini_file(__DIR__ . '/../../.env');
$base   = rtrim($config['NGROK_URL'], '/');

$link = $base . '/mykeeper/compras_guest?token=' . $token;

echo json_encode([
    'status'   => 'ok',
    'mensagem' => 'Link gerado com sucesso',
    'link'     => $link
]);

$stmt->close();
$conexao->close();
?>