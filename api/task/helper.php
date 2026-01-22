<?php

include dirname(__DIR__) . "/task/functions.php";


echo json_encode(task_completion_rate(isset($_GET['taskID']) ? $_GET['taskID'] : null));
