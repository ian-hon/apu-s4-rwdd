<!-- to use this, need to also include navbar.js -->
<!-- call toggleNavbar() -->
<div id="navbar" data-active="false">
    <div id="container" class="border">
        <a href="profile.php" id="profile-card" class="border">
            <img src="./assets/fire.svg">
            <h4>ajian_nedo</h4>
        </a>
        <hr>
        <a href="tasks.php" class="navbar-card">
            <img src="./assets/task.svg">
            <h4>TASKS</h4>
        </a>
        <!-- submission history here -->
        <a href="submission_history.php" class="navbar-card">
            <img src="./assets/task.svg">
            <h4>SUBMISSIONS</h4>
        </a>
        <a href="goals.php" class="navbar-card">
            <img src="./assets/target.svg">
            <h4>GOALS</h4>
        </a>
        <a href="leaderboard.php" class="navbar-card">
            <img src="./assets/leaderboard.svg">
            <h4>LEADERBOARD</h4>
        </a>
        <a href="points.php" class="navbar-card">
            <img src="./assets/leaf.svg">
            <h4>POINTS</h4>
            <!-- rewards, redemption history inside here -->
        </a>
        <div>
            <!-- this div is only shown if user is a curator or admin -->
            <hr>
            <a href="curator.php" class="navbar-card">
                <img src="./assets/leaf.svg">
                <h4>CURATOR DASHBOARD</h4>
            </a>
        </div>
    </div>
    <h4 id="close-button" onclick="toggleNavbar()" class="border">close</h4>
</div>