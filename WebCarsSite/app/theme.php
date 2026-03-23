<?php
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}
$theme = isset($_COOKIE['theme']) ? $_COOKIE['theme'] : 'light';

if(isset($_GET['theme'])) {
    $theme = ($_GET['theme'] == 'dark') ? 'dark' : 'light';
    setcookie('theme', $theme, time() + 86400 * 30, '/');
    header("Location: " . basename($_SERVER['PHP_SELF']));
    exit();
}

$_SESSION['theme'] = $theme;
?>