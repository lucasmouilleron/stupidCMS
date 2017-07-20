<?php

/////////////////////////////////////////////////////////////////////////////
$startStupid = microtime();
require_once __DIR__ . "/libs/stupidEngine.php";

/////////////////////////////////////////////////////////////////////////////
// Bootstrap
/////////////////////////////////////////////////////////////////////////////
$stupid = new Stupid();
$stupid->processPage($_GET["__page__"]);
$stupid->setDegubInfo("renderTime", (round((microtime() - $startStupid) * 1000, 3)) . " ms");

?>

<?php if(DEBUG_MODE) : ?>
    <div class="debug">DEBUG : <?php echo $stupid->getDebugInfos(); ?></div>
<?php endif; ?>