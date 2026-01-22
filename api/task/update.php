<?php
include dirname(__DIR__) . '/conn.php';
include dirname(__DIR__) . '/utils/update_helper.php';

$task_id = $_GET["id"];

$allowed_fields = array(
    "title",
    "description",
    "curator_instructions",
    "active",
    "target",
    "excess_limit",
    "reward_rate",
    "schedule",
    "goal_type",
    "goal_contribution",
    "occurance_type"
);

performUpdate($dbConnection, "TASK", "ID", $task_id, $allowed_fields, $_GET);
