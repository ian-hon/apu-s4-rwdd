<?php
include dirname(__DIR__) . '/conn.php';

$query = "SELECT * FROM ecoquest.ROLES";
$roles = array();

$queryResult = mysqli_query($dbConnection, $query);
foreach (mysqli_fetch_all($queryResult, MYSQLI_ASSOC) as $line) {
    $roles[$line['role']] = array(
        'role' => $line['role']
    );
}

echo json_encode($roles);
