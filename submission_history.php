<?php
include './api/conn.php'; // connects to the database

// total task
$totalTask = 'SELECT COUNT(task_ID) AS total_tasks FROM ecoquest.SUBMISSION WHERE user = "user1"';
$result = mysqli_query($dbConnection, $totalTask); // $dbConnection comes from conn.php
$allTask = mysqli_fetch_assoc($result); // fetch_assoc gets the first result and stores it inside $user

// total point
$totalPoint = "SELECT sum(task.reward_rate * submission.action_count) AS total_points FROM submission 
            INNER JOIN task ON submission.task_ID = task.ID WHERE submission.user = 'user1'";
$totalPointResult = mysqli_query($dbConnection, $totalPoint);
$allPoint = mysqli_fetch_assoc($totalPointResult);

$tasksQuery = "
    SELECT 
        task.description,
        task.reward_rate,
        task.excess_limit,
        submission.media,
        submission.status,
        submission.action_count
    FROM submission
    INNER JOIN task ON submission.task_ID = task.ID
    WHERE submission.`user` = 'user1'
";

$tasksResult = mysqli_query($dbConnection, $tasksQuery);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submission History | EcoQuest</title>
    <link rel="stylesheet" href="./styles/style.css">
    <link rel="stylesheet" href="./styles/submission_history.css">
</head>

<body>
    <div id="bg-color"></div>
    <!-- header -->
    <div id="header">
        <div class="top">
            <button id="back">
                <a href="./dashboard.php">
                    <img src="./assets/ivp/arrow-back-basic-svgrepo-com.svg" alt="">
                </a>
            </button>
            <h3>Submission History</h3>
            <button id="hamburger">
                <img src="./assets/burger.svg" alt="">
            </button>
        </div>
        <hr style="border: 0; height: 2px; background-color: #101309; margin: 0;">
    </div>

    <!-- element -->
    <div id="elements">
        <!-- Submisstion History Tracking -->
        <div id="submissionHistory">
            <div class="sh-tracking">
                <img src="./assets/ivp/leaf-svgrepo-com.svg" alt="">
                <p class="sh-tracking-p1">Total Tasks</p>
                <p class="sh-tracking-p2"><?php echo $allTask['total_tasks'] ?></p>
            </div>

            <div class="sh-tracking">
                <img src="./assets/ivp/tick-circle-svgrepo-com.svg" alt="">
                <p class="sh-tracking-p1">Points Earned</p>
                <p class="sh-tracking-p2"><?php echo $allPoint['total_points'] ?></p>
            </div>
        </div>

        <!-- All submission -->
        <div id="allSubmissions">
            <h3 id="header-allSubmission">All Submissions</h3>

            <?php
            while ($task = mysqli_fetch_assoc($tasksResult)) {

                $statusClass = '';

                if ($task['status'] === 'pending') {
                    $statusClass = 'orange';
                } elseif ($task['status'] === 'rejected') {
                    $statusClass = 'red';
                }
            ?>

                <div class="all-submts">
                    <img src="<?php echo $task['media']; ?>">

                    <div class="tasks-title">
                        <p class="all-submts-p1">
                            <?php echo $task['description']; ?>
                        </p>
                        <p class="all-submts-p2 <?php echo $statusClass; ?>">
                            <?php echo $task['status']; ?>
                        </p>
                    </div>

                    <div class="tasks-info">
                        <p class="all-submts-p3">
                            <?php echo $task['excess_limit']; ?> days ago
                        </p>
                        <p class="all-submts-p4">
                            <?php echo $task['reward_rate'] * $task['action_count']; ?> points
                        </p>
                    </div>
                </div>
            <?php } ?>
        </div>
        <?php include './components/navbar.php'; ?>
    </div>

    <script src="./scripts/script.js"></script>
    <script src="./scripts/navbar.js" defer></script>
    <script src="./scripts/submission_history.js" defer></script>
</body>

</html>