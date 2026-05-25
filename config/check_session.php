<?php
session_start();

header('Content-Type: application/json');

if(isset($_SESSION['logado']) && $_SESSION['logado'] === true){
    echo json_encode([
        'logado' => true,
        'id'      => $_SESSION['usuario']['id'],
        'nome'    => $_SESSION['usuario']['nome'],
        'tipo'    => $_SESSION['usuario']['tipo'],
        'redirect' => '/mykeeper/src/Views/home.php'
    ]);
}else{
    echo json_encode([
        'logado'   => false,
        'expirado' => true,   
        'mensagem' => 'Sessão expirada'
    ]);
}
