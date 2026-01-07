<?php
include  dirname(__DIR__) . '/conn.php';

$task_id = $_GET["id"];
$keys = array(
    "title" => null,
    "description" => null,
    "curator_instructions" => null,
    "active" => null,
    "target" => null,
    "excess_limit" => null,
    "reward_rate" => null,
    "schedule" => null,
    "goal_type" => null,
    "goal_contribution" => null,
    "occurance_type" => null
);

foreach ($_GET as $k => $v) {
    if (array_key_exists($k, $keys)) {
        $keys[$k] = $v;
    }
}

$updates = array();
foreach ($keys as $k => $v) {
    if (isset($v)) {
        // is this anti-injection necessary?
        $escaped_value = mysqli_real_escape_string($dbConnection, $v);
        $updates[] = "`$k` = '$escaped_value'";
    }
}

if (!empty($updates)) {
    $query = "UPDATE ecoquest.TASK SET " . implode(", ", $updates) . " WHERE ID = '" . $task_id . "'";
    echo "<script>console.log(" . '"' . $query . '"' . ")</script>";

    echo $query;
    mysqli_query($dbConnection, $query);
}
