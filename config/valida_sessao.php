<?php
session_start();

if (empty($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: /mykeeper/usuario_login");
    exit;
}
