<?php
include dirname(__DIR__) . '/conn.php';

$query = "SELECT * FROM ecoquest.POINTS";
$points = array();

$queryResult = mysqli_query($dbConnection, $query);
foreach (mysqli_fetch_all($queryResult, MYSQLI_ASSOC) as $line) {
    $points[$line['ID']] = array(
        'ID' => $line['ID'],
        'amount' => (int)$line['amount'],
        'timestamp' => (int)$line['timestamp'],
        'submission' => $line['submission']
    );
}

echo json_encode($points);
