<?php

/////////////////////////////////////////////////////////////////////////////
require_once __DIR__ . "/../libs/stupidBackend.php";
$stupidBackend = new stupidBackend();
$stupidBackend->lockPage();

/////////////////////////////////////////////////////////////////////////////
// Data collecting
/////////////////////////////////////////////////////////////////////////////
$contents = $stupidBackend->scanContents();
$files = $stupidBackend->scanFiles();

?>

<?php require_once __DIR__ . "/header.php"; ?>

    <div class="container">
        <div class="alert alert-success" role="alert">Found <strong><?php echo count($contents) ?></strong> contents</div>
        <div class="alert alert-success" role="alert">Found <strong><?php echo count($files) ?></strong> files</div>
    </div>

<?php require_once __DIR__ . "/footer.php"; ?>