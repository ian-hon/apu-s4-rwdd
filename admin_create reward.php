<?php
include './api/conn.php';

if (isset($_POST['submit_reward'])) {
    $title = mysqli_real_escape_string($dbConnection, $_POST['title']);
    $category = $_POST['category'];
    $points = $_POST['points'];
    $stock = $_POST['stock'];
    $auto_disc = isset($_POST['auto_discontinue']) ? 1 : 0;
    $dateCreated = date('Y-m-d H:i:s');

    $sql = "INSERT INTO ecoquest.REWARDS (title, category, points, stock, auto_discontinue, status, date_created) 
            VALUES ('$title', '$category', '$points', '$stock', '$auto_disc', 'active', '$dateCreated')";

    if (mysqli_query($dbConnection, $sql)) {
        header("Location: admin_dashboard.php?success=1"); // Redirect back
    } else {
        echo "Error: " . mysqli_error($dbConnection);
    }
}
?>
