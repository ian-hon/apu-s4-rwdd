<?php

function task_fetch_all($active = true)
{
    include dirname(__DIR__) . "/conn.php";

    $activeValue = $active ? 1 : 0;
    $query = "SELECT * FROM task WHERE active = $activeValue";
    $result = mysqli_query($dbConnection, $query);

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function task_fetch_daily_tasks()
{
    include dirname(__DIR__) . '/conn.php';

    // get day of week (0 = monday, 6 = sunday)
    $dayOfWeek = (intval(date('N')) - 1) % 7;

    $query = "SELECT * FROM task WHERE (schedule & (1 << $dayOfWeek)) != 0 AND active = 1";
    $result = mysqli_query($dbConnection, $query);

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function task_already_submitted($taskID, $username)
{
    // return true if there is a submission to this task on this day

    include dirname(__DIR__) . '/conn.php';

    $day = intval(time() / 86400); // todays epoch day (get current epoch and divide by 86400)
    $dayStart = $day * 86400;
    $dayEnd = ($day + 1) * 86400;

    $query = "SELECT * FROM submission 
              WHERE submission.submitted_timestamp BETWEEN $dayStart AND $dayEnd 
              AND submission.user = '$username' 
              AND submission.task_ID = '$taskID'";

    $result = mysqli_query($dbConnection, $query);

    return mysqli_num_rows($result) > 0;
}
