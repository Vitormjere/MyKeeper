<?php
    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }

    if($_SESSION['usuario']['tipo'] == 1){
       header("Location: /mykeeper/src/Views/admin_home.php");    
    }