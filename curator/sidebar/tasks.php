<div id="tasks">
    <?php
    include './api/task/functions.php';

    $tasks = task_fetch_all();

    ?>
    <div class="border">
        <h3><?php echo count($tasks) ?></h3>
        <h5>total tasks</h5>
    </div>
    <div id="daily" class="border">
        <h3><?php echo count(array_filter($tasks, function ($t) {
                return $t['occurance_type'] == 'daily';
            })) ?></h3>
        <h5>daily tasks</h5>
    </div>
    <div id="weekly" class="border">
        <h3><?php echo count(array_filter($tasks, function ($t) {
                return $t['occurance_type'] == 'weekly';
            })) ?></h3>
        <h5>weekly tasks</h5>
    </div>
    <div id="completion" class="border">
        <h3><?php echo intval(task_overall_completion_rate() * 100 * 100) / 100 ?>%</h3>
        <h5>overall completion rate</h5>
    </div>
</div>