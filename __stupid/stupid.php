<?php require_once __DIR__."/libs/stupidEngine.php";?>

<?php $stupid = new Stupid();?>
<?php $stupid->processPage($_GET["__page__"]);?>

<?php if(DEBUG_MODE) :?>
	<div class="debug">DEBUG : <?php echo $stupid->getDebugInfos();?></div>
<?php endif;?>