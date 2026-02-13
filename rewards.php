<?php 
session_start();
include './api/conn.php'; // connects to the database
include './api/utils/creation_util.php';
include './api/credentials.php';
include './api/points/functions.php';

// Fetch user current points
// $username comes from credentials.php
$totalPoints = points_get_current($username);

// Fetch avaiable rewards
$rewardsQuery = "SELECT * FROM ecoquest.REWARD WHERE active = 1 ORDER BY price ASC";
$rewardsResult = mysqli_query($dbConnection, $rewardsQuery);


// Redemption handling
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['redeem'])) {
    $rewardId = $_POST['reward_id'];
    
    // Fetch user points, check users points again
    $pointsQuery = "SELECT sum(task.reward_rate * submission.action_count) AS total_points 
                    FROM submission 
                    INNER JOIN task ON submission.task_ID = task.ID 
                    WHERE submission.user = '$username'";
    $pointsResult = mysqli_query($dbConnection, $pointsQuery);
    
    // Get reward details
    $rewardQuery = "SELECT * FROM REWARD WHERE ID = '$rewardId' AND active = 1";
    $rewardResult = mysqli_query($dbConnection, $rewardQuery);
    $reward = mysqli_fetch_assoc($rewardResult);

    // Check again again
    if ($reward && $totalPoints >= $reward['price'] && $reward['remaining'] > 0) {
        $redemptionId = generate_next_id($dbConnection, 'redemption', 'ID', 'RD_');
        $timestamp = time();
        $price = $reward['price'];
        
        $insertQuery = "INSERT INTO REDEMPTION (ID, reward_ID, user, timestamp, price) 
                       VALUES ('$redemptionId', '$rewardId', '$username', $timestamp, $price)";
        
        // Update remaining stock
        $updateQuery = "UPDATE REWARD SET remaining = remaining - 1 WHERE ID = '$rewardId'";
        
        if (mysqli_query($dbConnection, $insertQuery) && mysqli_query($dbConnection, $updateQuery)) {
            $redemptionSuccess = true;
        } else {
            $redemptionError = 'Failed to redeem reward';
        }
    } else {
        $redemptionError = 'Cannot redeem reward';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rewards | EcoQuest</title>
    <link rel="stylesheet" href="./styles/style.css">
    <link rel="stylesheet" href="./styles/rewards.css">
</head>

<body>
    <div id="bg-color"></div>
    <div id="header">
        <div class="top">
            <button id="back">
                <a href="./dashboard.php">
                    <img src="./assets/ivp/arrow-back-basic-svgrepo-com.svg" alt="">
                </a>
            </button>
            <h3>Rewards</h3>
            <button id="hamburger">
                <img src="./assets/burger.svg" alt="">
            </button>
        </div>
        <hr style="border: 0; height: 2px; background-color: #101309; margin: 0;">
    </div>

    <div id="elements">
        <div id="pointsBalance">
            <div class="points-card">
                <img src="./assets/ivp/tick-circle-svgrepo-com.svg" alt="">
                <div class="points-info">
                    <p class="points-label">Your Points</p>
                    <p class="points-value"><?php echo number_format($totalPoints); ?></p>
                </div>
            </div>
        </div>

        <div id="allRewards">
            <h3 id="header-allRewards">Available Rewards</h3>

            <?php
            $rewardCount = 0;
            while ($reward = mysqli_fetch_assoc($rewardsResult)) {
                $rewardCount++;
                $canRedeem = $totalPoints >= $reward['price'];
                $remaining = $reward['remaining'] ?? 0;
                $isOutOfStock = $remaining <= 0;
            ?>

                <div class="reward-card" data-reward-id="<?php echo $reward['ID']; ?>">
                    <img src="<?php echo htmlspecialchars($reward['media']); ?>" alt="<?php echo htmlspecialchars($reward['title']); ?>">

                    <div class="reward-header">
                        <div class="reward-title">
                            <p class="reward-name"><?php echo htmlspecialchars($reward['title']); ?></p>
                            <p class="reward-desc"><?php echo htmlspecialchars($reward['description']); ?></p>
                        </div>
                        <p class="reward-price"><?php echo number_format($reward['price']); ?> pts</p>
                    </div>

                    <div class="reward-footer">
                        <p class="reward-stock">
                            <?php echo $remaining > 0 ? $remaining . ' available' : 'Out of stock'; ?>
                        </p>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="reward_id" value="<?php echo $reward['ID']; ?>">
                            <button type="submit" name="redeem" class="redeem-btn <?php echo !$canRedeem || $isOutOfStock ? 'disabled' : ''; ?>" 
                                    <?php echo !$canRedeem || $isOutOfStock ? 'disabled' : ''; ?>>
                                <?php 
                                    if ($isOutOfStock) {
                                        echo 'Out of Stock';
                                    } elseif ($canRedeem) {
                                        echo 'Redeem';
                                    } else {
                                        echo number_format($reward['price'] - $totalPoints) . ' pts needed';
                                    }
                                ?>
                            </button>
                        </form>
                    </div>
                </div>
            <?php } ?>

            <?php if ($rewardCount === 0) { ?>
                <div class="no-rewards">
                    <p>No rewards available at the moment</p>
                </div>
            <?php } ?>
        </div>

        <?php include './components/navbar.php'; ?>
    </div>

    <div id="redeemed-popup" <?php echo isset($redemptionSuccess) ? 'style="display: flex;"' : ''; ?>>
        <div class="popup-content">
            <h2>Reward Redeemed!</h2>
            <p>Your reward has been successfully redeemed.</p>
            <div class="popup-buttons">
                <button id="continue-btn" class="popup-btn continue">Continue Browsing</button>
                <button id="history-btn" class="popup-btn history">View Redemption History</button>
            </div>
        </div>
    </div>

    <script src="./scripts/script.js"></script>
    <script src="./scripts/navbar.js" defer></script>
    <script src="./scripts/rewards.js" defer></script>
</body>

</html>