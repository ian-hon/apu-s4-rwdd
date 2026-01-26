<?php

$localhost = 'localhost';
$user = 'root';
$pass = '';
$dbName = 'ecoquest';

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$dbConnection = mysqli_connect($localhost, $user, $pass, $dbName);
if (mysqli_connect_errno()) {
    // echo "<script>console.log('failure');</script>";
    die('<script>alert("connection to database failed");</script>');
}

// echo "<script>console.log('success');</script>";
