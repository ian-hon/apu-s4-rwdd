<?php
include dirname(__DIR__) . '/conn.php';

$query = "SELECT * FROM ecoquest.GOAL_TYPE";
$goal_types = array();

$queryResult = mysqli_query($dbConnection, $query);
foreach (mysqli_fetch_all($queryResult, MYSQLI_ASSOC) as $line) {
    $goal_types[$line['ID']] = array(
        'ID' => $line['ID'],
        'term' => $line['term'],
        'unit' => $line['unit'],
        'decimal_points' => (int)$line['decimal_points']
    );
}

echo json_encode($goal_types);
