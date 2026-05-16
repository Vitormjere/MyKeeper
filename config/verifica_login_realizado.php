<?php

if(!empty($_SESSION['logado']) && $_SESSION['logado'] == true) {
    header("Location: /mykeeper/src/Views/home.php");
    exit;
};


