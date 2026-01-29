<?php
include dirname(__DIR__) . '/conn.php';
include dirname(__DIR__) . '/submission/functions.php';

// fix action counts first
submission_fix_action_count();

$query = "SELECT * FROM ecoquest.SUBMISSION";
$submissions = array();

$queryResult = mysqli_query($dbConnection, $query);
foreach (mysqli_fetch_all($queryResult, MYSQLI_ASSOC) as $line) {
    $submissions[$line['ID']] = array(
        'ID' => $line['ID'],
        'user' => $line['user'],
        'task_ID' => $line['task_ID'],
        // 'media' => $line['media'],
        'submitted_timestamp' => (int)$line['submitted_timestamp'],
        'action_count' => (int)$line['action_count'],
        'status' => $line['status'],
        'curator' => $line['curator']
    );
}

echo json_encode($submissions);
