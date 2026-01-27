<?php

function user_get_contribution_total($username, $time_start = NULL, $time_end = NULL)
{
    // returns assoc array of goal_types => contributions
    // eg: array(4) { ["carbon"]=> int(0) ["electric"]=> int(0) ["plastic"]=> int(1) ["trash"]=> int(0) }
    // $time_start and $time_end are optional, we use it for personal goals later
    // call either by
    //      user_get_contribution_total('username')
    //      or
    //      user_get_contribution_total('username', {start_time}, {end_time})

    include dirname(__DIR__) . '/conn.php';

    $query = "SELECT
    GOAL_TYPE.ID, COALESCE(
        (
        SELECT
            SUM(TASK.goal_contribution * SUBMISSION.action_count) as total
            from SUBMISSION
            inner join TASK on TASK.ID = SUBMISSION.task_ID
            where
                (SUBMISSION.user = '$username') AND
                (TASK.goal_type = GOAL_TYPE.ID) " .
        ((is_null($time_start) || is_null($time_end)) ? "" : "AND (SUBMISSION.submitted_timestamp BETWEEN '$time_start' AND '$time_end')")
        . "
        )
        , 0) as total FROM GOAL_TYPE;";
    $query_result = mysqli_query($dbConnection, $query);

    $result = array();
    foreach (mysqli_fetch_all($query_result) as $row) {
        $result[$row[0]] = intval($row[1]);
    }

    return $result;
}

function user_get_contribution_total_worded($username, $time_start = NULL, $time_end = NULL)
{
    // returns assoc array of goal_types => {
    //      'term': 'plastic saved',
    //      'total': 50,
    //      'unit': 'kg',
    //      'decimals': 1
    // }

    include dirname(__DIR__) . '/conn.php';

    $query = "SELECT
    GOAL_TYPE.*, COALESCE(
        (
        SELECT
            SUM(TASK.goal_contribution * SUBMISSION.action_count) as total
            from SUBMISSION
            inner join TASK on TASK.ID = SUBMISSION.task_ID
            where
                (SUBMISSION.user = '$username') AND
                (TASK.goal_type = GOAL_TYPE.ID) " .
        ((is_null($time_start) || is_null($time_end)) ? "" : "AND (SUBMISSION.submitted_timestamp BETWEEN '$time_start' AND '$time_end')")
        . "
        )
        , 0) as total FROM GOAL_TYPE;";
    $query_result = mysqli_query($dbConnection, $query);

    $result = array();
    foreach (mysqli_fetch_all($query_result, MYSQLI_ASSOC) as $row) {
        $result[$row['ID']] = array(
            'term' => $row['term'],
            'total' => floatval($row['total']),
            'unit' => $row['unit'],
            'decimals' => intval($row['decimal_points'])
        );
    }

    return $result;
}

function user_get_actions_total($username, $time_start = NULL, $time_end = NULL)
{
    include dirname(__DIR__) . '/conn.php';

    $query = "SELECT COALESCE(SUM(action_count), 0) as total FROM SUBMISSION 
              JOIN TASK on SUBMISSION.task_ID = TASK.ID 
              WHERE SUBMISSION.status = 'approved' AND SUBMISSION.user = '$username'" .
        ((is_null($time_start) || is_null($time_end)) ? "" : " AND (SUBMISSION.submitted_timestamp BETWEEN '$time_start' AND '$time_end')");

    $query_result = mysqli_query($dbConnection, $query);
    $row = mysqli_fetch_assoc($query_result);

    return intval($row['total']);
}


function user_get_streak($username)
{
    // get all the days the user has a submission, regardless of approved/rejected
    // days are calculated by submitted_timestamp / 86400 -> day number

    // thus, streak is the length of todays day number to the minimum unbroken one
    // for this, its just a O(n) linear search through descending list

    // eg:
    // day numbers : 10, 9, 8, 4
    //      streak : 3, len(10, 9, 8)

    include dirname(__DIR__) . '/conn.php';

    $query = "SELECT DISTINCT FLOOR(submitted_timestamp / 86400) as day 
              FROM SUBMISSION WHERE user = '$username' ORDER BY day DESC";
    $query_result = mysqli_query($dbConnection, $query);

    $today = floor(time() / (86400));
    $expected_day = $today - 1;
    // if you havent made submission since yesterday, your streak is still present
    // if you havent made submission since yesterday - 1, your streak is gone

    foreach (mysqli_fetch_all($query_result) as $row) {
        $day = intval($row[0]);

        // echo $day . "<br>";

        if ($day >= $expected_day) {
            $expected_day -= 1;
            // $expected_day = $day - 1;
        } else {
            // broken so return difference
            return $today - $expected_day - 1;
        }
    }

    // no way youll have full streak lol
    return $today - $expected_day - 1;
}

function user_get_streak_percentile($username)
{
    // return percentage of users that have lower streak than this user
    include dirname(__DIR__) . '/conn.php';

    $currentStreak = user_get_streak($username);

    $query = "SELECT DISTINCT user FROM SUBMISSION";
    $result = mysqli_query($dbConnection, $query);

    $totalUsers = 0;
    $usersWithLowerStreak = 0;

    foreach (mysqli_fetch_all($result) as $row) {
        $user = $row[0];
        $userStreak = user_get_streak($user);

        $totalUsers++;
        if ($userStreak < $currentStreak) {
            $usersWithLowerStreak++;
        }
    }

    if ($totalUsers == 0) {
        return 0;
    }

    return floor(($usersWithLowerStreak / $totalUsers) * 100);
}

function user_check_already_submitted_today($username)
{
    include dirname(__DIR__) . '/conn.php';

    $today = floor(time() / 86400);
    $dayStart = $today * 86400;
    // check range or just check if theres a submission after dayStart?
    $dayEnd = ($today + 1) * 86400;

    $query = "SELECT COUNT(*) as count FROM SUBMISSION 
              WHERE user = '$username' 
              AND submitted_timestamp BETWEEN $dayStart AND $dayEnd";

    $result = mysqli_query($dbConnection, $query);
    $row = mysqli_fetch_assoc($result);

    return intval($row['count']) > 0;
}
