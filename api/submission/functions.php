<?php

function submission_count_by_user($username)
{
    include dirname(__DIR__) . "/conn.php";

    $query = "SELECT COUNT(task_ID) AS total FROM submission WHERE user = '$username'";
    $result = mysqli_query($dbConnection, $query);
    $row = mysqli_fetch_assoc($result);

    return intval($row['total']);
}

function submission_fetch_by_user($username)
{
    include dirname(__DIR__) . "/conn.php";

    $query = "
        SELECT *, submission.ID as submission_ID, task.ID as task_ID FROM submission
        INNER JOIN task ON submission.task_ID = task.ID
        WHERE submission.user = '$username'
    ";

    $result = mysqli_query($dbConnection, $query);

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function submission_fix_action_count()
{
    include dirname(__DIR__) . "/conn.php";

    $query = "UPDATE submission 
            SET action_count = (SELECT target FROM task WHERE task.id = submission.task_id) 
            WHERE action_count < (SELECT target FROM task WHERE task.id = submission.task_id)";

    mysqli_query($dbConnection, $query);
}

function submission_fetch_all()
{
    include dirname(__DIR__) . "/conn.php";

    $query = "SELECT * FROM submission";
    $result = mysqli_query($dbConnection, $query);

    $submissions = array();
    foreach (mysqli_fetch_all($result, MYSQLI_ASSOC) as $row) {
        $submissions[$row['ID']] = $row;
    }

    return $submissions;
}

function submission_fetch_photo($submissionID)
{
    include dirname(__DIR__) . "/conn.php";

    $query = "SELECT media FROM submission WHERE ID = '$submissionID'";
    $result = mysqli_query($dbConnection, $query);
    $row = mysqli_fetch_assoc($result);

    return $row ? $row['media'] : '';
}
