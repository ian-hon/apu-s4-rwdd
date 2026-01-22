<?php

function performUpdate(
    $dbConnection, // get this from conn.php
    $table, // name of the table (eg: "USERS")
    $idField, // field for the id (eg: "USERS.username")
    $idValue, // the actual id (eg: "john_doe")
    $allowedFields, // array of the allowed fields (eg: ["username", "DOB"]; but not "role" because thats kinda dangerous)
    $data // array of data to update (eg: $_GET or $_POST)
) {
    // 1. create an empty array first
    $keys = array();
    foreach ($allowedFields as $field) {
        // assign each field as null
        //      username => null
        //      DOB => null
        $keys[$field] = null;
    }

    // 2. update that $keys array
    foreach ($data as $k => $v) {
        if (array_key_exists($k, $keys)) {
            // username => john_doe
            // DOB => 676767     
            $keys[$k] = $v;
        }
    }

    // 3. get a list of all th eupdates
    $updates = array();
    foreach ($keys as $k => $v) {
        // username => john_doe? then do something
        // DOB => null? then skip DOB
        if (isset($v)) {
            // anti-injection countermeasure
            $escaped_value = mysqli_real_escape_string($dbConnection, $v);
            $updates[] = "`$k` = '$escaped_value'";
        }
    }

    // 4. call the query using the $dbConnection
    if (!empty($updates)) {
        $escapedId = mysqli_real_escape_string($dbConnection, $idValue);
        $query = "UPDATE ecoquest.$table SET " . implode(", ", $updates) . " WHERE $idField = '" . $escapedId . "'";
        echo "<script>console.log(" . '"' . $table . "\n" . $query . '"' . ")</script>";
        echo $query;
        mysqli_query($dbConnection, $query);
    }
}
