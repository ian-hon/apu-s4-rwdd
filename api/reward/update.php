<?php
include dirname(__DIR__) . '/conn.php';
include dirname(__DIR__) . '/utils/update_helper.php';

$reward_id = $_GET["id"];

$allowed_fields = array(
    "title",
    "description",
    "price",
    "media",
    "active",
    "remaining",
    "initial"
);

performUpdate($dbConnection, "REWARD", "ID", $reward_id, $allowed_fields, $_GET);
