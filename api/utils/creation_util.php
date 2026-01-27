<?php

function generate_next_id($dbConnection, $table_name, $id_field, $prefix)
{
    $query = "SELECT {$id_field} FROM {$table_name} ORDER BY {$id_field} DESC LIMIT 1";
    $result = mysqli_query($dbConnection, $query);
    $last_id = 0; // fallback
    if ($row = mysqli_fetch_assoc($result)) {
        $last_id = intval(substr($row[$id_field], strlen($prefix)));
    }
    return $prefix . str_pad($last_id + 1, 4, '0', STR_PAD_LEFT);
}
