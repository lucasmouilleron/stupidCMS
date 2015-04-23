<?php require_once __DIR__."/libs/tools.php";?>

<?php echo @renderPage($_GET["__page__"]);?>

<?php if(DEBUG_MODE) :?>
	<div class="debug">DEBUG : <?php echo getDebugInfos();?></div>
<?php endif;?>