<?php
include '../api/conn.php';
include '../api/utils/creation_util.php';
include '../api/goal_type/functions.php';

$success = false;
$error = null;
$task = null;
$task_id = null;


if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $task_id = mysqli_real_escape_string($dbConnection, $_GET['id']);

    $query = "SELECT * FROM TASK WHERE ID = '$task_id'";
    $result = mysqli_query($dbConnection, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $task = mysqli_fetch_assoc($result);
    } else {
        $error = "Task not found.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // fetch all data
        $task_id = mysqli_real_escape_string($dbConnection, $_POST["task_id"]);

        $title = mysqli_real_escape_string($dbConnection, $_POST["title"]);
        $description = mysqli_real_escape_string($dbConnection, $_POST["description"]);
        $occurance_type = mysqli_real_escape_string($dbConnection, $_POST["occurance"]);
        $required_actions = intval($_POST["required_actions"]);
        $maximum_actions = intval($_POST["maximum_actions"]);
        $points_per_action = intval($_POST["points_per_action"]);
        $goal_type = mysqli_real_escape_string($dbConnection, $_POST["goal_type"]);
        $goal_contribution = floatval($_POST["goal_contribution"]);
        $curator_instructions = mysqli_real_escape_string($dbConnection, $_POST["curator_instructions"]);
        $schedule = 0;

        // then verify
        $occurance_type = (($occurance_type == "daily") || ($occurance_type == "weekly")) ? $occurance_type : "daily";
        if (count(array_filter(goal_type_fetch_all(), function ($v, $_) {
            global $goal_type;
            return $v['ID'] == $goal_type;
        }, ARRAY_FILTER_USE_BOTH)) < 1) {
            // idk what to do here
            throw new InvalidArgumentException("{$goal_type} is not a valid goal_type");
        }

        // insert
        $excess_limit = $maximum_actions - $required_actions;
        $update_query = "UPDATE TASK SET 
                        title = '$title', 
                        description = '$description', 
                        curator_instructions = '$curator_instructions', 
                        target = $required_actions, 
                        excess_limit = $excess_limit, 
                        reward_rate = $points_per_action, 
                        goal_type = '$goal_type', 
                        goal_contribution = $goal_contribution, 
                        occurance_type = '$occurance_type'
                        WHERE ID = '$task_id'";

        echo $update_query;

        mysqli_query($dbConnection, $update_query);

        $success = true;
    } catch (Exception $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task | EcoQuest</title>
    <link rel="stylesheet" href="./../styles/style.css">
    <link rel="stylesheet" href="./../styles/curator/edit_task.css">
</head>

<body>
    <div id="page">
        <?php if ($success): ?>
            <div id="success-content" class="border">
                <h2>Success!</h2>
                <p>Task updated successfully.</p>
                <button class="border" onclick="window.location.href='../curator.php'">Return to Dashboard</button>
            </div>
        <?php elseif ($error !== null): ?>
            <div id="error-content" class="border">
                <h2>Error!</h2>
                <p><?php echo htmlspecialchars($error); ?></p>
                <button class="border" onclick="window.location.href='../curator.php'">Return to Dashboard</button>
            </div>
        <?php else: ?>
            <a href="../curator.php" id="back-button" class="border">
                <h4>back to curator dashboard</h4>
            </a>
            <form id="form" class="border" method="POST" action="edit_task.php">
                <input type="hidden" name="task_id" value="<?php echo htmlspecialchars($task['ID']); ?>">
                <div id="title-and-occurance">
                    <div id="title" class="border">
                        <h5 class="box-title">TITLE</h5>
                        <input required name="title" id="title-input" placeholder="eg: Let's go recyling!" value="<?php echo htmlspecialchars($task['title']); ?>">
                    </div>
                    <div id="occurance">
                        <label for="daily">
                            <input required type="radio" id="daily" name="occurance" value="daily" <?php echo ($task['occurance_type'] == 'daily') ? 'checked' : ''; ?>>
                            <span>Daily</span>
                        </label>
                        <label for="weekly">
                            <input required type="radio" id="weekly" name="occurance" value="weekly" <?php echo ($task['occurance_type'] == 'weekly') ? 'checked' : ''; ?>>
                            <span>Weekly</span>
                        </label>
                    </div>
                </div>
                <div id="description" class="border">
                    <h5 class="box-title">DESCRIPTION</h5>
                    <textarea required name="description" id="description-input" type="textarea"
                        placeholder="eg: Recycle 2 plastic bottles. Send a picture of 2 empty plastic bottles in a recycling bin."><?php echo htmlspecialchars($task['description']); ?></textarea>
                </div>
                <hr>
                <div id="rewards">
                    <table id="data">
                        <tr>
                            <td>
                                <h5>Required actions:</h5>
                            </td>
                            <td><input id="required-actions" name="required_actions" type="number" required value="<?php echo $task['target']; ?>"></td>
                        </tr>
                        <tr>
                            <td>
                                <h5>Maximum actions:</h5>
                            </td>
                            <td><input id="maximum-actions" name="maximum_actions" type="number" required value="<?php echo ($task['target'] + $task['excess_limit']); ?>"></td>
                        </tr>
                        <tr>
                            <td>
                                <h5>Points per action:</h5>
                            </td>
                            <td>
                                <input id="points-per-action" name="points_per_action" type="number" required value="<?php echo $task['reward_rate']; ?>">
                                <img src="../assets/leaf.svg">
                            </td>
                        </tr>
                    </table>
                    <hr>
                    <div id="final">
                        <div id="required" class="border">
                            <div id="calculation">
                                <h6>2 x 20</h6>
                                <img src="../assets/leaf.svg">
                            </div>
                            <div id="result">
                                <h3>500</h3>
                                <img src="../assets/leaf.svg">
                            </div>
                            <h6 class="box-title">REQUIRED</h6>
                        </div>
                        <h3>+</h3>
                        <div id="optional" class="border">
                            <div id="calculation">
                                <h6>1 x 20</h6>
                                <img src="../assets/leaf.svg">
                            </div>
                            <div id="result">
                                <h3>400</h3>
                                <img src="../assets/leaf.svg">
                            </div>
                            <h6 class="box-title">OPTIONAL</h6>
                        </div>
                        <h3>=</h3>
                        <div id="maximum" class="border">
                            <div id="result">
                                <h3>900</h3>
                                <img src="../assets/leaf.svg">
                            </div>
                            <h6 class="box-title">MAXIMUM</h6>
                        </div>
                    </div>
                </div>
                <hr>
                <div id="goal" class="border">
                    <h5 class="box-title">GOAL CONTRIBUTION</h5>
                    <select id="dropdown" name="goal_type" required>
                        <?php foreach (goal_type_fetch_all() as $row): ?>
                            <option value="<?php echo $row['ID']; ?>" <?php echo ($task['goal_type'] == $row['ID']) ? 'selected' : ''; ?>><?php echo $row['term']; ?></option>
                        <?php endforeach; ?>
                        <!-- <option value="plastic">plastic waste</option>
                        <option value="carbon">CO2 offset</option>
                        <option value="electric">electricity saved</option>
                        <option value="trash">trash</option> -->
                    </select>
                    <input required id="goal-contribution" name="goal_contribution" step="0.0000000001" type="number" value="<?php echo $task['goal_contribution']; ?>">
                    <h5 id="goal-unit">kWh per action</h5>
                </div>
                <hr>
                <div id="curator-instructions" class="border">
                    <h5 class="box-title">CURATOR INSTRUCTIONS (OPTIONAL)</h5>
                    <textarea name="curator_instructions" id="curator-instructions-input"
                        placeholder="eg: Make sure picture has two plastic bottles and the bin is a proper recycling bin."><?php echo htmlspecialchars($task['curator_instructions']); ?></textarea>
                </div>
                <div id="actions">
                    <input class="border" type="reset" value="Reset form">
                    <input class="border" id="submit-button" type="submit" value="Update Task">
                </div>
            </form>
            <div id="errors" data-state="">
                <div id="container" class="border">

                </div>
                <h5>click anywhere to dismiss</h5>
            </div>
        <?php endif; ?>
    </div>

    <script src=" ./../scripts/script.js"></script>
    <script src="./../scripts/curator/edit_task.js" defer></script>
</body>

</html>