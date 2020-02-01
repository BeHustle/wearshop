<?php

session_name('session_id');
session_start(['cookie_lifetime' => 60 * 20]);
setcookie('session_id', session_id(), time() + 60 * 20, '/');
if (isset($_COOKIE['login'])) {
    setcookie('login', $_COOKIE['login'], time() + 60 *60 * 24 * 30, '/');
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/include/functions.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/include/menu_items.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/include/dbconfig.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/include/site_config.php');

if (isset($_SERVER['REDIRECT_URL'])) {
    $current_page = getCurrentPage($_SERVER['REDIRECT_URL'], $menu_items);
} else {
    $current_page = 'Главная';
}

$error = false;
$is_auth = false;

if (!empty($_POST['login']) && !empty($_POST['password'])) {
    $login = htmlspecialchars($_POST['login']);
    $password = htmlspecialchars($_POST['password']);
    $result = checkUser($login, $password);
    $result ? $is_auth = true : $error = true;
}

if ($is_auth && !isset($_COOKIE['login'])) {
    setcookie('login', $login, time() + 60 *60 *24 * 30, '/');
}

if ($_SESSION['is_auth']) {
    $is_auth = true;
}

if ($is_auth && !isset($_SESSION['is_auth'])) {
    $_SESSION['is_auth'] = $is_auth;
    $_SESSION['user_id'] = $result;
}

if ($_GET['login'] == 'no') {
    setcookie('login', null, -1, '/');
    session_destroy();
    header('location: /admin');
    die;
}

if ($_GET['logout'] == 'yes') {
    session_destroy();
    header('location: /admin');
    die;
}