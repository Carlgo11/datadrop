<?php

session_start();
if (!$_SESSION['name']) {
    header('Location: ' . 'dev-login.php');
}
?>