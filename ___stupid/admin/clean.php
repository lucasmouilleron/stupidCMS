<?php

/////////////////////////////////////////////////////////////////////////////
require_once __DIR__."/../libs/stupidBackend.php";
$stupidBackend = new stupidBackend();
$stupidBackend->lockPage();

/////////////////////////////////////////////////////////////////////////////
$deletedContents = $stupidBackend->cleanContents();
$deletedFiles = $stupidBackend->cleanFiles();

?>

<?php require_once __DIR__."/header.php";?>

<div class="container">

	<?php if(count($deletedContents) == 0):?>
		<div class="alert alert-success" role="alert">No contents to delete !</div>
	<?php else:?>
		<?php foreach ($deletedContents as $deletedContent) :?>
			<div class="alert alert-success" role="alert">Content <strong><?php echo $deletedContent?></strong> deleted</div>
		<?php endforeach;?>
	<?php endif;?>

	<?php if(count($deletedFiles) == 0):?>
		<div class="alert alert-success" role="alert">No files to delete !</div>
	<?php else:?>
		<?php foreach ($deletedFiles as $deletedFile) :?>
			<div class="alert alert-success" role="alert">File <strong><?php echo $deletedFile?></strong> deleted</div>
		<?php endforeach;?>
	<?php endif;?>

</div>

<?php require_once __DIR__."/footer.php";?>