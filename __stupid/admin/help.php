<?php

/////////////////////////////////////////////////////////////////////////////
require_once __DIR__."/../libs/stupidBackend.php";
$stupid = new stupidBackend();
$stupid->lockPage();
?>

<?php require_once __DIR__."/header.php";?>

<div class="container">
	<?php echo markdownToHTML(file_get_contents(README_FILE))?>
</div>

<?php require_once __DIR__."/footer.php";?>