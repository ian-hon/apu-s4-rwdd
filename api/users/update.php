<?php
include dirname(__DIR__) . '/conn.php';
include dirname(__DIR__) . '/utils/update_helper.php';

$username = $_GET["id"];

$allowed_fields = array(
    "password",
    "name",
    "DOB",
    "profile_picture"
);

performUpdate($dbConnection, "USERS", "username", $username, $allowed_fields, $_GET);
