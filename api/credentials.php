<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$username = $_SESSION['username'];
$password = $_SESSION['password'];

function redirect_to_login()
{
    header("Location: auth/login.php");
}

if (!isset($username) || !isset($password)) {
    redirect_to_login();
}

function enforce_role($role)
{
    global $username, $password;

    include_once dirname(__DIR__) . '/api/users/functions.php';

    $user = user_fetch($username);

    if ($user['password'] != $password) {
        redirect_to_login();
    }

    if ($user['role'] != $role) {
        redirect_to_login();
    }
}

function fetch_role($username)
{
    include_once dirname(__DIR__) . '/api/users/functions.php';

    return user_fetch($username)['role'];
}
