<?php
include 'api/conn.php'; 

if (isset($_POST['submit_reward'])) {
    $rewardID = $_POST['reward_id'] ?? null; 
    
    $title = $_POST['title'];
    $category = $_POST['category']; 
    $description = $_POST['description'];
    $price = $_POST['points'];
    $stock = $_POST['stock'];
    
    $mediaPath = "";
    if (!empty($_FILES["reward_image"]["name"])) {
        $targetDir = "uploads/rewards/"; 
        $fileName = time() . "_" . basename($_FILES["reward_image"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        if (move_uploaded_file($_FILES["reward_image"]["tmp_name"], $targetFilePath)) {
            $mediaPath = $targetFilePath;
        }
    }

    if ($rewardID) {
        if ($mediaPath != "") {
            $sql = "UPDATE REWARD SET title=?, category=?, description=?, price=?, media=?, remaining=? WHERE ID=?";
            $stmt = $dbConnection->prepare($sql);
            $stmt->bind_param("sssisiss", $title, $category, $description, $price, $mediaPath, $stock, $rewardID);
        } else {
            $sql = "UPDATE REWARD SET title=?, category=?, description=?, price=?, remaining=? WHERE ID=?";
            $stmt = $dbConnection->prepare($sql);
            $stmt->bind_param("sssiis", $title, $category, $description, $price, $stock, $rewardID);
        }
        $msg = "reward_updated";
    } else {
        $newID = "reward_" . time();
        $sql = "INSERT INTO REWARD (ID, title, category, description, price, media, active, remaining, initial) 
                VALUES (?, ?, ?, ?, ?, ?, 1, ?, ?)";
        $stmt = $dbConnection->prepare($sql);
        $stmt->bind_param("ssssisii", $newID, $title, $category, $description, $price, $mediaPath, $stock, $stock);
        $msg = "reward_created";
    }

    if ($stmt->execute()) {
        header("Location: admin.php?success=$msg");
        exit();
    } else {
        echo "Database Error: " . $stmt->error;
    }
}
?>