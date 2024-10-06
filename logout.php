<?php

session_status() === PHP_SESSION_ACTIVE ?: session_start();

$_SESSION = array();

if (session_destroy()) {
    header("Location: index.php");
}

?>
