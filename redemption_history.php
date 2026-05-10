<?php
include './api/conn.php'; // connects to the database
include './api/credentials.php';

// Fetch redemption history for the user
$redemptionQuery = "
    SELECT 
        redemption.ID,
        redemption.user,
        redemption.reward_ID,
        redemption.timestamp,
        reward.title,
        reward.description,
        reward.price,
        reward.media
    FROM ecoquest.REDEMPTION as redemption
    INNER JOIN ecoquest.REWARD as reward ON redemption.reward_ID = reward.ID
    WHERE redemption.user = '$username'
    ORDER BY redemption.timestamp DESC
";

$redemptionResult = mysqli_query($dbConnection, $redemptionQuery);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redemption History | EcoQuest</title>
    <link rel="stylesheet" href="./styles/style.css">
    <link rel="stylesheet" href="./styles/redemption_history.css">
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
            <h3>Redemption History</h3>
            <button id="hamburger">
                <img src="./assets/burger.svg" alt="">
            </button>
        </div>
        <hr style="border: 0; height: 2px; background-color: #101309; margin: 0;">
    </div>

    <!-- element -->
    <div id="elements">
        <!-- All Redemptions -->
        <div id="allRedemptions">
            <h3 id="header-allRedemptions">Your Redemptions</h3>

            <?php
            $redemptionCount = 0;
            while ($redemption = mysqli_fetch_assoc($redemptionResult)) {
                $redemptionCount++;
                $timestamp = new DateTime();
                $timestamp->setTimestamp($redemption['timestamp']);
                $formattedDate = $timestamp->format('M d, Y');
                $timeAgo = $timestamp->diff(new DateTime())->format('%d days ago');
            ?>

                <div class="redemption-card">
                    <img src="<?php echo htmlspecialchars($redemption['media']); ?>" alt="<?php echo htmlspecialchars($redemption['title']); ?>">

                    <div class="redemption-info">
                        <div class="redemption-header">
                            <div class="redemption-title">
                                <p class="redemption-name"><?php echo htmlspecialchars($redemption['title']); ?></p>
                                <p class="redemption-desc"><?php echo htmlspecialchars($redemption['description']); ?></p>
                            </div>
                            <p class="redemption-price"><?php echo number_format($redemption['price']); ?> pts</p>
                        </div>

                        <div class="redemption-footer">
                            <p class="redemption-date"><?php echo $timeAgo; ?></p>
                            <p class="redemption-fulldate"><?php echo $formattedDate; ?></p>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <?php if ($redemptionCount === 0) { ?>
                <div class="no-redemptions">
                    <p>No redemptions yet</p>
                </div>
            <?php } ?>
        </div>

        <?php include './components/navbar.php'; ?>
    </div>

    <script src="./scripts/script.js"></script>
    <script src="./scripts/navbar.js" defer></script>
    <script src="./scripts/redemption_history.js" defer></script>
</body>

</html>
