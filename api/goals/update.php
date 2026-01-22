<?php
include dirname(__DIR__) . '/conn.php';
include dirname(__DIR__) . '/utils/update_helper.php';

$goal_id = $_GET["id"];

$allowed_fields = array(
    "title",
    "description",
    "media",
    "goal_type",
    "goal",
    "starting_time",
    "ending_time",
    "type"
);

performUpdate($dbConnection, "GOALS", "ID", $goal_id, $allowed_fields, $_GET);
