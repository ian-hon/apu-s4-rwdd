<?php

function redemption_get_total_redeemed($username)
{
    include dirname(__DIR__) . "/conn.php";

    $query = "SELECT COALESCE(SUM(price), 0) as total FROM redemption WHERE user = '$username'";
    $result = mysqli_query($dbConnection, $query);
    $row = mysqli_fetch_assoc($result);
    return $row ? intval($row['total']) : 0;
}
