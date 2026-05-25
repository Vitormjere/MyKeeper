<?php
    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }

    if (empty($_SESSION['admin']) || $_SESSION['admin'] !== true) {
        header("Location: /mykeeper/src/Views/admin_login.php");
        exit;
    }