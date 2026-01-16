<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <meta name="viewport" content="initial-scale=1, maximum-scale=1"> -->

    <title>Dashboard | EcoQuest</title>
    <link rel="stylesheet" href="./styles/style.css">
    <link rel="stylesheet" href="./styles/dashboard.css">
</head>

<body>
    <div id="page">
        <div id="parent">
            <div id="header">
                <div id="stats">
                    <div id="leaves">
                        <h4>
                            4,200
                        </h4>
                        <img src="./assets/leaf.svg">
                    </div>
                    <div id="streak">
                        <img src="./assets/fire.svg">
                        <h4>
                            50
                        </h4>
                    </div>
                </div>
                <img onclick="toggleNavbar()" id="burger" src="./assets/burger.svg">
            </div>
            <div id="streak-info" class="border">
                <h6 class="box-title">CURRENT STREAK</h6>
                <img src="./assets/fire.svg" class="border">
                <div id="details">
                    <h2>
                        50 days
                    </h2>
                    <h5>
                        better than 80% of users!
                    </h5>
                </div>
            </div>
            <div id="tasks">
                <div id="title">
                    <h3>DAILY TASKS</h3>
                    <div id="timer">
                        <h6>new tasks in</h6>
                        <h6 class="countdown"></h6>
                    </div>
                </div>
                <div id="task-container">
                    <div class="task-card">
                        <img id="icon" class="border" src="./assets/leaf.svg">
                        <div id="details">
                            <h4>Eat a leaf</h4>
                            <h6>Leaves are good for health</h6>
                        </div>
                        <div id="points">
                            <h6>500</h6>
                            <img src="./assets/leaf.svg">
                        </div>
                    </div>
                    <div class="task-card">
                        <img id="icon" class="border" src="./assets/leaf.svg">
                        <div id="details">
                            <h4>Eat a leaf</h4>
                            <h6>Leaves are good for health</h6>
                        </div>
                        <div id="points">
                            <h6>500</h6>
                            <img src="./assets/leaf.svg">
                        </div>
                    </div>
                    <div class="task-card">
                        <img id="icon" class="border" src="./assets/leaf.svg">
                        <div id="details">
                            <h4>Eat a leaf</h4>
                            <h6>Leaves are good for health</h6>
                        </div>
                        <div id="points">
                            <h6>500</h6>
                            <img src="./assets/leaf.svg">
                        </div>
                    </div>
                </div>
            </div>
            <div id="goals">
                <h3 id="title">GLOBAL GOALS</h3>
                <div id="goal-container">
                    <div class="goal-card">
                        <div id="header">
                            <img src="./assets/plastic.svg" class="border">
                            <div id="details">
                                <h4>
                                    Plastic saved
                                </h4>
                                <h5>
                                    5,440 contributors
                                </h5>
                            </div>
                        </div>
                        <div id="data">
                            <div>
                                <div id="details">
                                    <h2 id="target">
                                        40.5
                                    </h2>
                                    <h5>/ 50 kg</h5>
                                </div>
                                <h6>83% progress</h6>
                            </div>
                            <div class="progressbar">
                                <div id="thumb"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="goal-container">
                    <div class="goal-card">
                        <div id="header">
                            <img src="./assets/cloud.svg" class="border">

                            <div id="details">
                                <h4>
                                    CO<sub>2</sub> offset
                                </h4>
                                <h5>
                                    1,553 contributors
                                </h5>
                            </div>
                        </div>
                        <div id="data">
                            <div>
                                <div id="details">
                                    <h2 id="target">
                                        25.3
                                    </h2>
                                    <h5>/ 30 tonnes</h5>
                                </div>
                                <h6>83% progress</h6>
                            </div>
                            <div class="progressbar">
                                <div id="thumb"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include './components/navbar.php' ?>
    </div>
    <script src="./scripts/script.js"></script>
    <script src="./scripts/navbar.js" defer></script>
    <script src="./scripts/dashboard.js"></script>
</body>

</html>