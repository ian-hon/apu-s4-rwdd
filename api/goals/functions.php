<?php

// get progress
// get total contributions (singular and all)
// get all completed
// get goal completion rate
//      30% above average, etc


// $type = either 'personal' or 'global'
function goals_contributions_all($username, $type = NULL, $onlyActive = true)
{
    include dirname(__DIR__) . '/conn.php';

    $query = "select *, coalesce((
        select
    	sum(task.goal_contribution * submission.action_count)
	    from submission
            inner join task
            on task.ID = submission.task_ID
        where
            (submission.user = '$username') and
            (submission.status = 'approved') and
            (task.goal_type = goals.goal_type)
            " . (is_null($type) ? "" : "and (goals.type = '$type')") . "
            " . ($onlyActive ? "and (submission.submitted_timestamp between goals.starting_time and goals.ending_time)" : "") . "
    ), 0) as total from goals";
    $queryResult = mysqli_query($dbConnection, $query);

    $result = array();
    foreach (mysqli_fetch_all($queryResult, MYSQLI_ASSOC) as $row) {
        $result[$row['ID']] = array(
            'ID' => $row['ID'],
            'title' => $row['title'],
            'description' => $row['description'],
            'media' => $row['media'],
            'goal_type' => $row['goal_type'],
            'goal' => $row['goal'],
            'starting_time' => intval($row['starting_time']),
            'ending_time' => intval($row['ending_time']),
            'type' => $row['type'],
            'total' => floatval($row['total']),
        );
    }
    return $result;
}

function goals_contributions($username, $goalID, $type = NULL, $onlyActive = true)
{
    return goals_contributions_all($username, $type, $onlyActive)[$goalID];
}

function goals_progress($username, $goalID)
{
    // returns from 0-100 the progress of the goal
    $goal = goals_contributions($username, $goalID, type: 'personal');
    return floor(($goal['total'] / $goal['goal']) * 100);
}

function goals_all_completed($username, $type)
{
    $result = array();
    foreach (goals_contributions_all($username) as $item) {
        if ($item['total'] >= $item['goal']) {
            $result[] = $item;
        }
    }
    return $result;
}

function goals_completion_rate($goalID = NULL)
{
    include dirname(__DIR__) . '/conn.php';

    /*
    output:
    GOALS.ID, completion_rate
    GL_0001 => 20,
    GL_0002 => 0,
    ...
    */

    $query = "
        SELECT 
            goals.ID,
            (COUNT(DISTINCT user_totals.user) * 100.0) / (SELECT COUNT(*) FROM users) as completion_rate
        FROM goals
        LEFT JOIN (
            SELECT 
                submission.user,
                task.goal_type,
                SUM(submission.action_count * task.goal_contribution) as total
            FROM submission
            INNER JOIN task ON task.ID = submission.task_ID
            WHERE submission.status = 'approved'
            GROUP BY submission.user, task.goal_type
        ) as user_totals 
            ON user_totals.goal_type = goals.goal_type
            AND user_totals.total >= goals.goal
        GROUP BY goals.ID
    ";

    $queryResult = mysqli_query($dbConnection, $query);

    $result = array();
    foreach (mysqli_fetch_all($queryResult, MYSQLI_ASSOC) as $row) {
        $result[$row['ID']] = floatval($row['completion_rate']);
    }

    if (is_null($goalID)) {
        return $result;
    } else {
        return $result[$goalID];
    }
}

function goals_overall_completion_rate()
{
    // primitive lol, dont wanna write sql for this
    $total = 0;
    $count = 0;

    foreach (goals_completion_rate() as $row) {
        $count++;
        $total += $row;
    }

    if ($count == 0) {
        return 0;
    }
    return $total / $count;
}
