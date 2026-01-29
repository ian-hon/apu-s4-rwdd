<?php
include dirname(__DIR__) . '/conn.php';

$query = "SELECT * FROM ecoquest.REWARD";
$rewards = array();

$queryResult = mysqli_query($dbConnection, $query);
foreach (mysqli_fetch_all($queryResult, MYSQLI_ASSOC) as $line) {
    $rewards[$line['ID']] = array(
        'ID' => $line['ID'],
        'title' => $line['title'],
        'description' => $line['description'],
        'price' => (int)$line['price'],
        'media' => $line['media'],
        'active' => ((int)$line['active']) == 1,
        'remaining' => (int)$line['remaining'],
        'initial' => (int)$line['initial'],
        'category' =>$line['category']
    );
}

echo json_encode($rewards);
