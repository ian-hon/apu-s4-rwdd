<!-- to use this, need to also include navbar.js -->
<!-- call toggleNavbar() -->
<div id="navbar" data-active="false">
    <div id="container" class="border">
        <a href="profile.php" id="profile-card" class="border">
            <?php
            include_once dirname(__DIR__) . '/api/users/functions.php';

            $pfp = user_fetch_pfp($username);
            if ($pfp):
            ?>
                <img src="data:image/jpeg;base64,<?php echo base64_encode($pfp); ?>" alt="Profile Picture">
            <?php else: ?>
                <img src="./assets/ivp/profile-picture.avif" alt="Default Profile Picture">
            <?php endif; ?>
            <h4><?php echo htmlspecialchars($username); ?></h4>
        </a>
        <hr>
        <a href="dashboard.php" class="navbar-card">
            <img src="./assets/home.svg">
            <h4>HOME</h4>
        </a>
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
        <a href="rewards.php" class="navbar-card">
            <img src="./assets/gift.svg">
            <h4>REWARDS</h4>
        </a>
        <a href="redemption_history.php" class="navbar-card">
            <img src="./assets/leaf.svg">
            <h4>REDEMPTIONS</h4>
        </a>
    </div>
    <h4 id="close-button" onclick="toggleNavbar()" class="border">close</h4>
</div>