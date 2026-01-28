<?php

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
            <div id="navbar-top">
                <a href="dashboard.php">
                    <img src="./assets/ivp/arrow-back-basic-svgrepo-com.svg" alt="">
                </a>
                <h3>Task Submission</h3>
                <button id="hamburger">
                    <img src="./assets/burger.svg" alt="">
                </button>
                <hr style="background-color: #222; border: none; height: 2px; width: 100%; grid-column: span 3; margin-top: 10px; margin-bottom: 0;">
            </div>

            <!-- element -->
            <div id="element">

                <!-- Tasks -->
                <div id="tasks">
                    <div id="task-header">
                        <img src="./assets/ivp/water-drops-svgrepo-com (1).svg" alt="">
                        <p>Eat a leaf</p>
                    </div>
                    <p id="task-body">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore
                        et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
                        aliquip ex ea commodo consequat.
                    </p>
                    <div id="points"> + 100 points</div>

                    <div id="cameraBtn">
                        <a href="">
                            <img src="./assets/ivp/camera-svgrepo-com.svg" alt="">
                            <p>Upload Proof</p>
                        </a>
                    </div>
                </div>

                <!-- Camera -->
                <form action="" method="POST" id="camera">
                    <!-- user for front cam / environment for back cam -->
                    <input type="file" accept="image/*" capture="environment" id="camera-input">

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

                    <div class="submission-lists">
                        <div class="userInfo">
                            <img src="./assets/ivp/profile-picture.avif" alt="">
                            <p class="name">User1</p>
                        </div>

                        <div class="status">
                            <p>Approved</p>
                        </div>
                    </div>
                    <div class="submission-lists">
                        <div class="userInfo">
                            <img src="./assets/ivp/profile-picture.avif" alt="">
                            <p class="name">User1</p>
                        </div>

                        <div class="status">
                            <p>Approved</p>
                        </div>
                    </div>
                    <div class="submission-lists">
                        <div class="userInfo">
                            <img src="./assets/ivp/profile-picture.avif" alt="">
                            <p class="name">User1</p>
                        </div>

                        <div class="status">
                            <p>Approved</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <?php include './components/navbar.php'; ?>
    </div>

    <script src="./scripts/script.js"></script>
    <script src="./scripts/navbar.js" defer></script>
    <script src="./scripts/task_submission.js" defer></script>
</body>

</html>