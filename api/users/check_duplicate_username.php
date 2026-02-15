<?php

include dirname(__DIR__) . '/conn.php';

if (isset($_GET['username'])) {
    $username = $_GET['username'];

    $sql = "SELECT COUNT(*) as count FROM USERS WHERE username = '$username'";
    $result = mysqli_query($dbConnection, $sql);
    $row = mysqli_fetch_assoc($result);

    if ($row['count'] > 0) {
        echo json_encode(['exists' => true]);
    } else {
        echo json_encode(['exists' => false]);
    }
} else {
    echo json_encode(['exists' => false]);
}
