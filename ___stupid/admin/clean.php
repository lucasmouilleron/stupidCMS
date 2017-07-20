<?php

/////////////////////////////////////////////////////////////////////////////
require_once __DIR__ . "/../libs/stupidBackend.php";
$stupidBackend = new stupidBackend();
$stupidBackend->lockPage();

/////////////////////////////////////////////////////////////////////////////
// Processing
/////////////////////////////////////////////////////////////////////////////
if(isset($_GET["do"]))
{
    $deletedContents = $stupidBackend->cleanContents();
    $deletedFiles = $stupidBackend->cleanFiles();
}

?>

<?php require_once __DIR__ . "/header.php"; ?>

    <div class="container">

        <div class="alert alert-danger" role="alert">
            <p><strong>Beware !</strong></p>
            <p>Are you sure you want to delete unreferenced files or contents ?</p>
            <p><a class="btn btn-warning" href="?do">Yes, proceed !</a></p>
        </div>

        <?php if(isset($deletedContents)): ?>
            <?php if(count($deletedContents) == 0): ?>
                <div class="alert alert-success" role="alert">No contents to delete !</div>
            <?php else: ?>
                <?php foreach($deletedContents as $deletedContent) : ?>
                    <div class="alert alert-success" role="alert">Content <strong><?php echo $deletedContent ?></strong> deleted</div>
                <?php endforeach; ?>
            <?php endif; ?>
        <?php endif; ?>

        <?php if(isset($deletedFiles)): ?>
            <?php if(count($deletedFiles) == 0): ?>
                <div class="alert alert-success" role="alert">No files to delete !</div>
            <?php else: ?>
                <?php foreach($deletedFiles as $deletedFile) : ?>
                    <div class="alert alert-success" role="alert">File <strong><?php echo $deletedFile ?></strong> deleted</div>
                <?php endforeach; ?>
            <?php endif; ?>
        <?php endif; ?>

    </div>

<?php require_once __DIR__ . "/footer.php"; ?>