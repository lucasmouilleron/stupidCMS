<?php

/////////////////////////////////////////////////////////////////////////////
require_once __DIR__."/../libs/stupidBackend.php";
$stupidBackend = new stupidBackend();
$stupidBackend->lockPage();

/////////////////////////////////////////////////////////////////////////////
$cacheFiles = $stupidBackend->stupid->clearCache();

?>

<?php require_once __DIR__."/header.php";?>

<div class="container">
	<?php foreach ($cacheFiles["pages"] as $cacheFile) :?>
		<div class="alert alert-success" role="alert">Page cache <strong><?php echo $cacheFile?></strong> generated</div>
	<?php endforeach;?>

	<?php foreach ($cacheFiles["contents"] as $cacheFile) :?>
		<div class="alert alert-success" role="alert">Content cache <strong><?php echo $cacheFile?></strong> generated</div>
	<?php endforeach;?>
</div>

<?php require_once __DIR__."/footer.php";?>