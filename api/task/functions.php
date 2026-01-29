<?php

function task_completion_rate($taskID = null)
{
    include dirname(__DIR__) . "/conn.php";

    $result = array();
    $taskQuery = "SELECT ID FROM task";
    $taskResult = mysqli_query($dbConnection, $taskQuery);
    foreach (mysqli_fetch_all($taskResult, MYSQLI_ASSOC) as $row) {
        $result[$row['ID']] = 0;
    }

    $query = "SELECT 
        task_id,
        COUNT(CASE WHEN status = 'approved' THEN 1 END) as approved,
        COUNT(*) as total
    FROM SUBMISSION
    GROUP BY task_id";

    $queryResult = mysqli_query($dbConnection, $query);

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

function task_overall_completion_rate()
{
    $total = 0;
    $count = 0;

    foreach (task_completion_rate() as $row) {
        $count++;
        $total += $row;
    }

    if ($count == 0) {
        return 0;
    }
    return $total / $count;
}

function task_fetch_all($active = true)
{
    include dirname(__DIR__) . "/conn.php";

    if (is_null($active)) {
        $query = "SELECT * FROM task";
    } else {
        $activeValue = $active ? 1 : 0;
        $query = "SELECT * FROM task WHERE active = $activeValue";
    }

    $result = mysqli_query($dbConnection, $query);

    $tasks = array();
    foreach (mysqli_fetch_all($result, MYSQLI_ASSOC) as $row) {
        $tasks[$row['ID']] = $row;
    }

    return $tasks;
}

function task_fetch_daily_tasks()
{
    include dirname(__DIR__) . '/conn.php';

    // get day of week (0 = monday, 6 = sunday)
    $dayOfWeek = (intval(date('N')) - 1) % 7;

    $query = "SELECT * FROM task WHERE occurance_type = 'daily' AND (schedule & (1 << $dayOfWeek)) != 0 AND active = 1";
    $result = mysqli_query($dbConnection, $query);

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function task_fetch_weekly_tasks()
{
    include dirname(__DIR__) . '/conn.php';
    include dirname(__DIR__) . '/utils/time_util.php';

    $currentWeek = getEpochWeek(time() * 1000);

    $query = "SELECT * FROM task WHERE occurance_type = 'weekly' AND schedule = $currentWeek AND active = 1";
    $result = mysqli_query($dbConnection, $query);

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function task_fetch_all_ongoing()
{
    return array_merge(task_fetch_daily_tasks(), task_fetch_weekly_tasks());
}

function task_already_submitted($taskID, $username)
{
    // return true if there is a submission to this task on this day

    include dirname(__DIR__) . '/conn.php';

    $taskQuery = "SELECT occurance_type FROM task WHERE ID = '$taskID'";
    $taskResult = mysqli_query($dbConnection, $taskQuery);
    $task = mysqli_fetch_assoc($taskResult);

    if (!$task) {
        return false;
    }

    if ($task['occurance_type'] == 'weekly') {
        include_once dirname(__DIR__) . '/utils/time_util.php';
        $currentWeek = getEpochWeek(time() * 1000);
        $weekStart = ($currentWeek * 7 - 3) * 86400;
        $weekEnd = (($currentWeek + 1) * 7 - 3) * 86400;

        $query = "SELECT * FROM submission 
                  WHERE submission.submitted_timestamp BETWEEN $weekStart AND $weekEnd 
                  AND submission.user = '$username' 
                  AND submission.task_ID = '$taskID'";
    } else {
        $day = intval(time() / 86400);
        $dayStart = $day * 86400;
        $dayEnd = ($day + 1) * 86400;

        $query = "SELECT * FROM submission 
                  WHERE submission.submitted_timestamp BETWEEN $dayStart AND $dayEnd 
                  AND submission.user = '$username' 
                  AND submission.task_ID = '$taskID'";
    }

    $result = mysqli_query($dbConnection, $query);

    return mysqli_num_rows($result) > 0;
}
