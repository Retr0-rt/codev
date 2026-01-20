<?php
require_once "Includes/functions.php";
session_start();
post_log(get_db_connection(),"{$_SESSION['username']} just logged out",$_SESSION['username']);
$_SESSION = array();


if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();
header("Location: login.php?msg=" . urlencode("See you later ajmi"));
exit;