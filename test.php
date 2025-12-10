<?php

$conn = new mysqli("localhost", "root", "", "ecoquest");

// https://www.php.net/manual/en/mysqli-result.fetch-all.php
// https://www.php.net/manual/en/mysqli.constants.php#constant.mysqli-assoc
// $r = mysqli_query($conn, "SELECT * FROM USERS;");
// print_r($r->fetch_all(MYSQLI_ASSOC));

// $username = $_POST["username"];
// $password = $_POST["password"];

$username = "user1";
$password = "hashed_password_789";

echo "attempt login with {$username}<br>";

if (isset($username) && isset($password)) {
    $result = $conn->query("SELECT * FROM USERS WHERE username = '{$username}' LIMIT 1");
    if ($result->num_rows >= 1) {
        // username exists
        $record = $result->fetch_assoc();
        if ($password == $record['password']) {
            echo "password matches";
        } else {
            echo "password no match";
        }
    } else {
        // username doesnt exist
    }
}
