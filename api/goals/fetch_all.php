<?php
include dirname(__DIR__) . '/conn.php';

$query = "SELECT * FROM ecoquest.GOALS";
$goals = array();

$queryResult = mysqli_query($dbConnection, $query);
foreach (mysqli_fetch_all($queryResult, MYSQLI_ASSOC) as $line) {
    $goals[$line['ID']] = array(
        'ID' => $line['ID'],
        'title' => $line['title'],
        'description' => $line['description'],
        'media' => $line['media'],
        'goal_type' => $line['goal_type'],
        'goal' => (float)$line['goal'],
        'starting_time' => (int)$line['starting_time'],
        'ending_time' => (int)$line['ending_time'],
        'type' => $line['type']
    );
}

echo json_encode($goals);
