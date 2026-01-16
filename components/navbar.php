<!-- to use this, need to also include navbar.js -->
<!-- call toggleNavbar() -->
<div id="navbar" data-active="false">
    <div id="container" class="border">
        <a href="profile.html" id="profile-card" class="border">
            <img src="./assets/fire.svg">
            <h4>ajian_nedo</h4>
        </a>
        <hr>
        <a href="tasks.html" class="navbar-card">
            <img src="./assets/task.svg">
            <h4>TASKS</h4>
        </a>
        <a href="achievements.html" class="navbar-card">
            <img src="./assets/trophy.svg">
            <h4>ACHIEVEMENTS</h4>
        </a>
        <a href="goals.html" class="navbar-card">
            <img src="./assets/target.svg">
            <h4>GOALS</h4>
        </a>
        <a href="leaderboard.html" class="navbar-card">
            <img src="./assets/leaderboard.svg">
            <h4>LEADERBOARD</h4>
        </a>
        <a href="points.html" class="navbar-card">
            <img src="./assets/leaf.svg">
            <h4>POINTS</h4>
        </a>
        <div>
            <!-- this div is only shown if user is a curator or admin -->
            <hr>
            <a href="curator.html" class="navbar-card">
                <img src="./assets/leaf.svg">
                <h4>CURATOR DASHBOARD</h4>
            </a>
        </div>
    </div>
    <h4 id="close-button" onclick="toggleNavbar()" class="border">close</h4>
</div>