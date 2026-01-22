<?php
include dirname(__DIR__) . '/conn.php';

$query = "SELECT * FROM ecoquest.USERS";
$users = array();

$queryResult = mysqli_query($dbConnection, $query);
foreach (mysqli_fetch_all($queryResult, MYSQLI_ASSOC) as $line) {
    $users[$line['username']] = array(
        'username' => $line['username'],
        'password' => $line['password'],
        'name' => $line['name'],
        'DOB' => (int)$line['DOB'],
        'profile_picture' => $line['profile_picture'],
        'role' => $line['role']
    );
}

echo json_encode($users);
