<?php

function points_get_current($username)
{
    include dirname(__DIR__) . "/conn.php";
    include dirname(__DIR__) . "/redemption/functions.php";

    return points_get_total($username) - redemption_get_total_redeemed($username);
}

function points_get_total($username)
{
    include dirname(__DIR__) . "/conn.php";

    $query = "SELECT SUM(task.reward_rate * submission.action_count) as total 
            FROM submission 
            JOIN task ON submission.task_id = task.id 
            WHERE submission.user = '$username' AND submission.status = 'approved'";

    $result = mysqli_query($dbConnection, $query);
    $row = mysqli_fetch_assoc($result);

    return intval($row['total'] ?? 0);
}
