<?php
include dirname(__DIR__) . '/conn.php';
include dirname(__DIR__) . '/utils/update_helper.php';

$id = $_GET["id"];

$allowed_fields = array(
    "user",
    "task_ID",
    "media",
    "submitted_timestamp",
    "action_count",
    "status",
    "curator"
);

performUpdate($dbConnection, "SUBMISSION", "ID", $id, $allowed_fields, $_GET);
