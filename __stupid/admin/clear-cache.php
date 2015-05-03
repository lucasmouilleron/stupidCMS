<?php

/////////////////////////////////////////////////////////////////////////////
require_once __DIR__."/../libs/stupidBackend.php";
$stupidBackend = new stupidBackend();
$stupidBackend->lockPage();

/////////////////////////////////////////////////////////////////////////////
$cacheFiles = $stupidBackend->stupid->clearSMTECache();

?>

<?php require_once __DIR__."/header.php";?>

<div class="container">
	<?php foreach ($cacheFiles as $cacheFile) :?>
		<div class="alert alert-success" role="alert">Cache file <strong><?php echo $cacheFile?></strong> generated</div>
		
	<?php endforeach;?>
</div>

<?php require_once __DIR__."/footer.php";?>