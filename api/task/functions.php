<?php

function task_completion_rate($taskID = null)
{
    include dirname(__DIR__) . "/conn.php";

    $query = "
        SELECT 
            task_id,
            COUNT(CASE WHEN status = 'approved' THEN 1 END) as approved,
            COUNT(*) as total
        FROM SUBMISSION
        GROUP BY task_id
    ";

    $queryResult = mysqli_query($dbConnection, $query);

    $result = array();
    foreach (mysqli_fetch_all($queryResult, MYSQLI_ASSOC) as $row) {
        $rate = $row['total'] > 0 ? floatval($row['approved']) / floatval($row['total']) : 0;
        $result[$row['task_id']] = $rate;
    }

    if (is_null($taskID)) {
        return $result;
    } else {
        return isset($result[$taskID]) ? $result[$taskID] : 0;
    }
}
