<?php

function user_fetch($username)
{
    include dirname(__DIR__) . '/conn.php';

    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($dbConnection, $query);

    return mysqli_fetch_assoc($result);
}
