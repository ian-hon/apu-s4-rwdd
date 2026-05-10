<?php

function user_fetch($username)
{
    include dirname(__DIR__) . '/conn.php';

    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($dbConnection, $query);

    return mysqli_fetch_assoc($result);
}

function user_fetch_pfp($username)
{
    include dirname(__DIR__) . '/conn.php';

    $username = mysqli_real_escape_string($dbConnection, $username);
    $query = "SELECT profile_picture FROM users WHERE username = '$username'";
    $result = mysqli_query($dbConnection, $query);
    $row = mysqli_fetch_assoc($result);

    return $row ? $row['profile_picture'] : null;
}
