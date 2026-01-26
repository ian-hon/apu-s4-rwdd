<?php
include  dirname(__DIR__) . '/conn.php';

$occurance_type = isset($_GET['occurance_type']) ? $_GET['occurance_type'] : null;

$query = "select * from ecoquest.TASK" . (is_null($occurance_type) ? "" : " where occurance_type = '{$occurance_type}'");

$tasks = array();

$queryResult = mysqli_query($dbConnection, $query);
foreach (mysqli_fetch_all($queryResult, MYSQLI_ASSOC) as $line) {
    $tasks[$line['ID']] = array(
        'ID' => $line['ID'],
        'title' => $line['title'],
        'description' => $line['description'],
        'curator_instructions' => $line['curator_instructions'],

        'active' => ((int)$line['active']) == 1,
        'target' => (int)$line['target'],
        'excess_limit' => (int)$line['excess_limit'],
        'reward_rate' => (int)$line['reward_rate'],
        'goal_contribution' => (float)$line['goal_contribution'],
        'schedule' => (int)$line['schedule'],

        'goal_type' => $line['goal_type'],

        'occurance_type' => $line['occurance_type'],
    );
}

echo json_encode($tasks);
