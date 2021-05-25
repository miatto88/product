<?php
session_start();

$_SESSION = [];

if (isset($_COOKIE["PHPSESSID"])) {
    setcookie("PHPSESSID", '', time() - 1800, '/');
}

session_destroy();


header("Location: login.php");
exit();

?>