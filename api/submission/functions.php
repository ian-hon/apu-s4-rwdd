<?php

function submission_fix_action_count()
{
    include dirname(__DIR__) . "/conn.php";

    $query = "UPDATE submission 
            SET action_count = (SELECT target FROM task WHERE task.id = submission.task_id) 
            WHERE action_count < (SELECT target FROM task WHERE task.id = submission.task_id)";

    mysqli_query($dbConnection, $query);
}
