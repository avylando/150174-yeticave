<?php

session_start();

if (!empty($_SESSION) && isset($_SESSION['user'])) {
    unset($_SESSION['user']);
}

header('Location: /index.php');
exit();
