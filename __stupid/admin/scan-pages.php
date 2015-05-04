<?php

/////////////////////////////////////////////////////////////////////////////
require_once __DIR__."/../libs/stupidBackend.php";
$stupidBackend = new stupidBackend();
$stupidBackend->lockPage();

?>

<?php $images = $stupidBackend->scanImages();?>
<?php $contents = $stupidBackend->scanContents();?>

<?php require_once __DIR__."/header.php";?>

<div class="container">
	<div class="alert alert-success" role="alert">Found <strong><?php echo count($contents)?></strong> contents</div>
	<div class="alert alert-success" role="alert">Found <strong><?php echo count($images)?></strong> images</div>
</div>

<?php require_once __DIR__."/footer.php";?>