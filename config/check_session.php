<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    header('Content-Type: application/json');

    if (isset($_SESSION['usuario']['id'])) {
        // Verifica expiração
        $agora = time();
        $ultimaAtividade = $_SESSION['ultima_atividade'] ?? $agora;

        if (($agora - $ultimaAtividade) > 10) { // mesmo timeout
            session_unset();
            session_destroy();
            echo json_encode([
                'logado'   => false,
                'expirado' => true,
                'mensagem' => 'Sessão expirada'
            ]);
            exit;
        }

        $_SESSION['ultima_atividade'] = $agora;

        echo json_encode([
            'logado'   => true,
            'id'       => $_SESSION['usuario']['id'],
            'nome'     => $_SESSION['usuario']['nome'],
            'tipo'     => $_SESSION['usuario']['tipo'],
            'redirect' => '/mykeeper/src/Views/home.php'
        ]);
    } else {
        echo json_encode([
            'logado'   => false,
            'expirado' => false,
            'mensagem' => 'Não autenticado'
        ]);
    }