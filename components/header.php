<!--

how to use:

$headerTitle = 'Dashboard';
include 'header.php';

-->

<div id="component-header">
    <img src="./assets/arrow_back.svg" onclick="history.back()">
    <h3><?php echo $headerTitle; ?></h3>
    <img src="./assets/burger.svg" onclick="toggleNavbar()">
</div>
<?php include dirname(__DIR__) . "/components/navbar.php" ?>
<script src="../scripts/navbar.js"></script>