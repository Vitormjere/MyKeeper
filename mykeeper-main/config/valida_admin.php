<?php
    session_start();
    if (empty($_SESSION['admin']) || $_SESSION['admin'] !== true) {
        header("Location: admin_login.php");
        exit;
    }
