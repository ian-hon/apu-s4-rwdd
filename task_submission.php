<?php
include './api/conn.php';
include './api/task/functions.php';
include './api/points/functions.php';
include './api/credentials.php';
include './api/users/functions.php';

$tasks = task_fetch_all();
$taskID = $_GET['ID'];
$currentTask = $tasks[$taskID];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include './api/utils/creation_util.php';

    $image = file_get_contents($_FILES['submission-image']['tmp_name']);
    $newID = generate_next_id($dbConnection, 'submission', 'ID', 'SU_');

    $query = "INSERT INTO submission (ID, user, task_ID, media, submitted_timestamp, action_count, status, curator)
    VALUES (
        '$newID',
        '$username',
        '$taskID',
        '" . mysqli_real_escape_string($dbConnection, $image) . "',
        " . time() . ",
        " . $currentTask['target'] . ",
        'pending',
        NULL
    )";

    mysqli_query($dbConnection, $query);

    header('Location: ./submission_history.php');
}

// point
$points = $currentTask['target'] * $currentTask['reward_rate'];

$sql = "SELECT * FROM submission INNER JOIN users 
            WHERE submission.user = users.username 
            AND submission.task_ID = '$taskID'";
$userSubmitted = mysqli_query($dbConnection, $sql);
$submittedResult = $userSubmitted;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Submission | EcoQuest</title>
    <link rel="stylesheet" href="./styles/style.css">
    <link rel="stylesheet" href="./styles/task_submission.css">

</head>

<body>
    <div id="parent">
        <div id="bg-color">

            <!-- navbar -->
            <?php $headerTitle = "Task Submission";
            include './components/header.php' ?>

            <!-- element -->
            <div id="element">

                <!-- Tasks -->
                <div id="tasks">
                    <div id="task-header">
                        <img src="./assets/ivp/water-drops-svgrepo-com (1).svg" alt="">
                        <p><?php echo $currentTask['title'] ?></p>
                    </div>
                    <p id="task-body">
                        <?php echo $currentTask['description'] ?>
                    </p>
                    <div id="points"><?php echo $points ?></div>

                    <div id="cameraBtn">
                        <a href="">
                            <img src="./assets/ivp/camera-svgrepo-com.svg" alt="">
                            <p>Upload Proof</p>
                        </a>
                    </div>
                </div>

                <!-- Camera -->
                <form action="" method="POST" id="camera" enctype="multipart/form-data">
                    <!-- user for front cam / environment for back cam -->
                    <input type="file" accept="image/*" capture="environment" id="submission-image" name='submission-image'>

                    <div id="actual-picture">
                        <img src="./assets/ivp/camera-svgrepo-com.svg" alt="">
                    </div>

                    <div id="actionBtn">
                        <input type="reset" id="cancelBtn" value="Cancel">
                        <input type="submit" id="submitBtn" value="Submit">
                    </div>
                </form>

                <!-- Recent submission -->
                <div id="submission">
                    <div id="submission-header">
                        <h2>Recent Submission</h2>
                    </div>

                    <?php
                    foreach ($submittedResult as $row) {
                        $statusClass = '';

                        if ($row['status'] === 'pending') {
                            $statusClass = 'orange';
                        } elseif ($row['status'] === 'rejected') {
                            $statusClass = 'red';
                        }
                    ?>
                        <div class="submission-lists">
                            <div class="userInfo">
                                <?php 
                                    $userPfp = user_fetch_pfp($row['username']);
                                    if ($userPfp): 
                                ?>
                                    <img src="data:image/jpeg;base64,<?php echo base64_encode($userPfp); ?>" alt="Profile Picture">
                                <?php else: ?>
                                    <img src="./assets/ivp/profile-picture.avif" alt="Default Profile Picture">
                                <?php endif; ?>
                                <p class="name"><?php echo $row['username'] ?></p>
                            </div>

                            <div class="status <?php echo $statusClass; ?>">
                                <p><?php echo $row['status'] ?></p>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>

        </div>
    </div>

    <script src="./scripts/script.js"></script>
    <script src="./scripts/task_submission.js" defer></script>
</body>

</html>