<?php

$statusFilter = $_GET['status'];
$queryFilter = $_GET['query'];

include '../../api/submission/functions.php';
include '../../api/task/functions.php';

$submissions = submission_fetch_all();
$tasks = task_fetch_all(null);

function getParsedExcessCount($submission, $tasks)
{
    $t = $tasks[$submission['task_ID']];
    return $submission['action_count'] - $t['target'];
}

function totalPoints($submission, $tasks)
{
    $t = $tasks[$submission['task_ID']];
    return $t['reward_rate'] * $submission['action_count'];
}

function humanReadableTime($epoch)
{
    $d = new DateTime();
    $d->setTimestamp($epoch);
    $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    return $d->format('j') . ' ' . $months[$d->format('n') - 1] . ' at ' . $d->format('H:i');
}

?>
<?php foreach (
    array_filter($submissions, function ($s) use ($statusFilter, $queryFilter, $tasks) {
        if (isset($statusFilter) && ($s['status'] != $statusFilter)) {
            return false;
        }

        $t = $tasks[$s['task_ID']];
        if (isset($queryFilter)) {
            $searchFields = [
                strtolower($t['title']),
                strtolower($t['description']),
                strtolower($s['user']),
                strtolower(humanReadableTime($s['submitted_timestamp'])),
            ];

            // is there some sort of any() function we can use here?
            $matches = array_filter($searchFields, function ($text) use ($queryFilter) {
                // is this the fastest way to check?
                return strpos($text, strtolower($queryFilter)) !== false;
            });

            if (count($matches) == 0) {
                return false;
            }
        }

        return true;
    }) as $row
): ?>
    <?php
    $t = $tasks[$row['task_ID']];
    ?>
    <div class="submission-card" data-status="<?php echo $row['status']; ?>" data-occurance="<?php echo $t['occurance_type']; ?>">
        <div id="header">
            <!-- is there a justify-self thing that can do this? -->
            <span>
                <img id="pfp" src="./assets/fire.svg">
                <div id="info">
                    <h4><?php echo $row['user']; ?></h4>
                    <h5><?php echo humanReadableTime($row['submitted_timestamp']); ?></h5>
                </div>
            </span>
            <h5 id="tag" class="border"><?php echo strtoupper($row['status']); ?></h5>
        </div>
        <div id="image">
            <img src="data:image/jpeg;base64,<?php echo base64_encode(submission_fetch_photo($row['ID'])) ?>" />
        </div>
        <div id="data" class="border">
            <div id="task">
                <div id="info">
                    <h5 id="title"><?php echo $t['title']; ?></h5>
                    <h6 id="description"><?php echo $t['description']; ?></h6>
                </div>
                <h6 id="occurance" class="border"><?php echo strtoupper($t['occurance_type']); ?></h6>
            </div>
            <!-- show excess only if applicable -->
            <div id="excess">
                <div id="details">
                    <h5>Enter excess:</h5>
                    <div id="actions">
                        <h5 onclick="incrementExcess(-1, '<?php echo $row['ID']; ?>')">-</h5>
                        <h5><?php echo getParsedExcessCount($row, $tasks); ?></h5>
                        <h5 onclick="incrementExcess(1, '<?php echo $row['ID']; ?>')">+</h5>
                    </div>
                </div>
                <div id="points" class="border">
                    <h6><?php echo totalPoints($row, $tasks); ?></h6>
                    <img src="./assets/leaf.svg">
                </div>
            </div>
            <div id="instructions">
                <!-- the (!) img icon here -->
                <!-- <img> -->
                <h6>
                    <!-- could use ::before element here -->
                    NOTE TO CURATORS :
                </h6>
                <h6><?php echo $t['curator_instructions']; ?></h6>
            </div>
        </div>
        <div id="actions">
            <!-- icons next to these? -->
            <h4 class="border" id="reject" onclick="updateSubmissionStatus('rejected', '<?php echo $row['ID']; ?>')">
                REJECT
            </h4>
            <h4 class="border" id="approve" onclick="updateSubmissionStatus('approved', '<?php echo $row['ID']; ?>')">
                APPROVE
            </h4>
        </div>
    </div>
<?php endforeach; ?>