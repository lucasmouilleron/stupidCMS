<?php $startStupid = microtime()?>
<?php require_once __DIR__."/libs/stupidEngine.php";?>

<?php $stupid = new Stupid()?>
<?php $stupid->processPage($_GET["__page__"])?>
<?php $stupid->setDegubInfo("renderTime",(round((microtime()-$startStupid)*1000,3))." ms")?>

<?php if(DEBUG_MODE) :?>
	<div class="debug">DEBUG : <?php echo $stupid->getDebugInfos();?></div>
<?php endif;?>