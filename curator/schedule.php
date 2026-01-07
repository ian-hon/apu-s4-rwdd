<?php
include './api/conn.php';
// include '../api/conn.php';

$query = "select * from ecoquest.TASK";

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
        'goal_contribution' => (int)$line['goal_contribution'],
        'schedule' => (int)$line['schedule'],

        'goal_type' => $line['goal_type'],

        'occurance_type' => $line['occurance_type'],
    );
}

function totalPoints($task)
{
    return $task["reward_rate"] * $task["target"];
}

function getTasksFromDay($day)
{
    global $tasks;
    $result = array();

    foreach ($tasks as $t) {
        if ((($t["schedule"] >> $day) & 1) == 1) {
            $result[$t["ID"]] = $t;
        }
    }

    return $result;
}

function getDayMap($task)
{
    $result = array();
    $d = $task['schedule'];

    for ($i = 0; $i < 7; $i++) {
        $result[$i] = ($d & 1) == 1;
        $d >>= 1;
    }
    return $result;
}

?>

<div id="schedule">
    <p>
    </p>
    <div id="header">
        <h2>TASK SCHEDULING</h2>
        <h5>Assign a task to each day of the week</h5>
    </div>
    <div id="overview" class="border">
        <?php

        $days = ["monday", "tuesday", "wednesday", "thursday", "friday", "saturday", "sunday"];

        foreach ($days as $index => $day) {
            $content = "";
            foreach (getTasksFromDay($index) as $t) {
                $content .= '<div class="task-minimal-card">
                    <h5>' . htmlspecialchars($t['title']) . '</h5>
                    <div id="rewards" class="border">
                        <h5>' . totalPoints($t) . '</h5>
                        <img src="./assets/leaf.svg">
                    </div>
                </div>';
            }

            echo "<div class='day' id='{$day}'>
                <h3 id='title' class='border'>" . strtoupper($day) . "</h3>
                <div id='container'>{$content}</div>
            </div>";

            if ($index != 6) {
                echo "<hr>";
            }
        }

        ?>
    </div>
    <div id="selector" class="border">
        <h3>Tasks</h3>
        <div id="container">
            <?php

            foreach ($tasks as $task) {
                $dayContent = "";
                $days = ['M', 'T', 'W', 'T', 'F', 'S', 'S'];
                foreach (getDayMap($task) as $index => $m) {
                    $dayContent .= "<h4 class='border' " . ($m ? 'data-active' : '') . ">" . $days[$index] . "</h4>";
                }

                echo "<div class='task-selector-card'>
                    <div id='data'>
                        <span>
                            <h4>" . htmlspecialchars($task['title']) . "</h4>
                            <h5>" . htmlspecialchars($task['description']) . "</h5>
                        </span>
                        <div id='rewards' class='border'>
                            <h5>" . totalPoints($task) . "</h5>
                            <img src='./assets/leaf.svg'>
                        </div>
                    </div>
                    <hr>
                    <div id='select'>{$dayContent}</div>
                </div>";
            }

            ?>
        </div>
    </div>
</div>