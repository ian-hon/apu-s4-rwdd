<?php

function goal_type_fetch_all()
{
    include dirname(__DIR__) . "/conn.php";

    return mysqli_fetch_all(mysqli_query($dbConnection, "SELECT * FROM GOAL_TYPE"), MYSQLI_ASSOC);
}
