<?php
    session_start();

    define('SENHA_ADMIN', 'senhaforte'); 

    if ($_POST['senha'] === SENHA_ADMIN) {
        $_SESSION['admin'] = true;
        echo json_encode(['status' => 'ok']);
    } else {
        echo json_encode(['status' => 'nok', 'mensagem' => 'Senha incorreta']);
    }