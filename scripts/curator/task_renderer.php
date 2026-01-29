<?php

include '../../api/task/functions.php';
include_once '../../api/utils/time_util.php';

$tasks = task_fetch_all(true);
$completionRates = task_completion_rate();

function totalPoints($task)
{
    return $task['reward_rate'] * ($task['target'] + $task['excess_limit']);
}

function getDayMap($task)
{
    $result = [];
    $d = $task['schedule'];
    for ($i = 0; $i < 7; $i++) {
        $result[] = ($d & 1) == 1;
        $d >>= 1;
    }
    return $result;
}

?>
<?php foreach ($tasks as $t): ?>
    <?php
    $dayMap = getDayMap($t);
    $days = ['M', 'T', 'W', 'T', 'F', 'S', 'S'];
    ?>
    <div class="task-card-detailed" data-active="<?php echo $t['active']; ?>" data-occurance="<?php echo $t['occurance_type']; ?>">
        <div id="header">
            <div id="data">
                <span>
                    <h3><?php echo $t['title']; ?></h3>
                    <h6 class="border" id="occurance-tag"></h6>
                    <div class="border" id="completion-rate">
                        <img src="./assets/completion_rate.svg">
                        <h5><?php echo round($completionRates[$t['ID']] * 100 * 100) / 100; ?>% completion rate</h5>
                    </div>
                </span>
                <h5><?php echo $t['description']; ?></h5>
            </div>
            <div id="actions">
                <a href="./curator/edit_task.php?id=<?php echo urlencode($t['ID']); ?>">
                    <img class="border" src="./assets/edit.svg">
                </a>
                <img class="border" src="./assets/trash.svg" onclick="askDeleteTask('<?php echo $t['ID']; ?>')">
            </div>
        </div>
        <div id="holder">
            <div id="scheduled-days" class="border">
                <h5 class="box-title">SCHEDULED FOR</h5>
                <?php if ($t['occurance_type'] == 'daily'): ?>
                    <div id="days">
                        <?php foreach ($dayMap as $index => $isActive): ?>
                            <h4 class='border' <?php echo $isActive ? 'data-active' : ''; ?> onclick='toggleDay("<?php echo $t['ID']; ?>", <?php echo $index; ?>)'><?php echo $days[$index]; ?></h4>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div id="weeks">
                        <h4 class='border' <?php echo (getEpochWeek(time() * 1000) == $t['schedule']) ? 'data-active' : ''; ?> onclick='toggleWeek("<?php echo $t['ID']; ?>", getEpochWeek(new Date()))'>THIS WEEK</h4>
                        <h4 class='border' <?php echo ((getEpochWeek(time() * 1000) + 1) == $t['schedule']) ? 'data-active' : ''; ?> onclick='toggleWeek("<?php echo $t['ID']; ?>", getEpochWeek(new Date()) + 1)'>NEXT WEEK</h4>
                    </div>
                <?php endif; ?>
            </div>
            <div id="rewards">
                <div id="required" class="border">
                    <div id="calculation">
                        <h6><?php echo $t['target']; ?> x <?php echo $t['reward_rate']; ?></h6>
                        <img src="./assets/leaf.svg">
                    </div>
                    <div id="result">
                        <h3><?php echo $t['target'] * $t['reward_rate']; ?></h3>
                        <img src="./assets/leaf.svg">
                    </div>
                    <h6 class="box-title">REQUIRED</h6>
                </div>
                <h3>+</h3>
                <div id="optional" class="border">
                    <div id="calculation">
                        <h6><?php echo $t['excess_limit']; ?> x <?php echo $t['reward_rate']; ?></h6>
                        <img src="./assets/leaf.svg">
                    </div>
                    <div id="result">
                        <h3><?php echo $t['excess_limit'] * $t['reward_rate']; ?></h3>
                        <img src="./assets/leaf.svg">
                    </div>
                    <h6 class="box-title">OPTIONAL</h6>
                </div>
                <h3>=</h3>
                <div id="maximum" class="border">
                    <div id="result">
                        <h3><?php echo totalPoints($t); ?></h3>
                        <img src="./assets/leaf.svg">
                    </div>
                    <h6 class="box-title">MAXIMUM</h6>
                </div>
            </div>
        </div>
        <div id="curator-note">
            <h5><?php echo $t['curator_instructions']; ?></h5>
        </div>
    </div>
<?php endforeach; ?>