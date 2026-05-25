<?php
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: DENY');
    header('X-XSS-Protection: 1; mode=block');

    if (session_status() == PHP_SESSION_NONE) {
    session_start();
    }

    define('SESSION_TIMEOUT', 600); 

    if (!empty($_SESSION['usuario']['id'])) {
        $agora = time();
        $ultimaAtividade = $_SESSION['ultima_atividade'] ?? $agora;

        if (($agora - $ultimaAtividade) > SESSION_TIMEOUT) {
            // Tempo expirado — destrói a sessão
            session_unset();
            session_destroy();

            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'status'   => 'nok',
                'mensagem' => 'Sessão expirada por inatividade'
            ]);
            exit;
        }
        
        $_SESSION['ultima_atividade'] = $agora;
    }