<?php
include dirname(__DIR__) . '/conn.php';

$query = "SELECT * FROM ecoquest.PERSONAL_GOALS";
$personal_goals = array();

$queryResult = mysqli_query($dbConnection, $query);
foreach (mysqli_fetch_all($queryResult, MYSQLI_ASSOC) as $line) {
    $personal_goals[$line['ID']] = array(
        'ID' => $line['ID'],
        'user' => $line['user'],
        'title' => $line['title'],
        'description' => $line['description'],
        'media' => $line['media'],
        'goal_type' => $line['goal_type'],
        'goal' => (float)$line['goal'],
        'starting_time' => (int)$line['starting_time'],
        'ending_time' => (int)$line['ending_time']
    );
}

echo json_encode($personal_goals);
