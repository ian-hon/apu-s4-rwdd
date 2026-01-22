<?php
include dirname(__DIR__) . '/conn.php';

$query = "SELECT * FROM ecoquest.REDEMPTION";
$redemptions = array();

$queryResult = mysqli_query($dbConnection, $query);
foreach (mysqli_fetch_all($queryResult, MYSQLI_ASSOC) as $line) {
    $redemptions[$line['ID']] = array(
        'ID' => $line['ID'],
        'reward_ID' => $line['reward_ID'],
        'user' => $line['user'],
        'timestamp' => (int)$line['timestamp'],
        'price' => (int)$line['price']
    );
}

echo json_encode($redemptions);
