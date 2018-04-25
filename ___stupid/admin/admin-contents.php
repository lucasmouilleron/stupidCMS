<?php

/////////////////////////////////////////////////////////////////////////////
require_once __DIR__ . "/../libs/stupidBackend.php";
$stupidBackend = new stupidBackend();
$stupidBackend->lockPage();

/////////////////////////////////////////////////////////////////////////////
// Data collecting
/////////////////////////////////////////////////////////////////////////////
$contents = $stupidBackend->listContents();
$contentsByPages = $stupidBackend->listContentsByPages();

/////////////////////////////////////////////////////////////////////////////
// Fallback form processing (default ajax)
/////////////////////////////////////////////////////////////////////////////
$saved = false;
$itemSaved = "";
$scroll = 0;
if(isset($_POST["item"]))
{
    $itemSaved = $stupidBackend->saveContent($_POST["item"], $_POST["content"]);
    $scroll = $_POST["scroll"];
    $saved = true;
}
if($saved)
{
    $stupidBackend->stupid->clearCache();
    $stupidBackend->scanContents();
    $stupidBackend->scanFiles();
    $contents = $stupidBackend->listContents();
    $contentsByPages = $stupidBackend->listContentsByPages();
}

?>

<?php require_once __DIR__ . "/header.php"; ?>

<?php if($scroll != 0): ?>
    <div id="scroll" data-scroll="<?php echo $scroll; ?>"></div>
<?php endif; ?>

<div class="modal fade" role="dialog" aria-hidden="true" id="preview-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Preview</h4>
            </div>
            <div class="modal-body">
                <span id="preview-modal-content"></span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div id="sidebar" style="display: none">
    <div id="toc-toggle">&#x25BC;</div>
    <div id="toc" data-headers="h2"></div>
</div>

<div id="contents-filter" class="filter">
    <input type="text" placeholder="search ..." class="form-control"/>
</div>

<div class="container">
    <?php if($saved): ?>
        <div class="alert alert-success" role="alert"><?php echo $itemSaved ?> <strong>saved</strong> !</div>
    <?php endif; ?>

    <?php ksort($contentsByPages); ?>

    <?php foreach($contentsByPages as $contentPage => $contents) : ?>
        <div class="content-page page-container">
            <a name="<?php echo $contentPage ?>"></a>
            <h2><?php echo $contentPage ?><!--<sup><?php echo count($contents) ?></sup>--></h2>

            <?php foreach($contents as $content): ?>
                <?php $contentFilePath = $stupidBackend->stupid->getContentFilePath($content["name"]); ?>
                <?php createFileIfNotExists($contentFilePath) ?>
                <div class="content">
                    <a name="<?php echo $contentPage . "/" . $content["name"] ?>"></a>
                    <h3><?php echo $content["name"] ?><?php if($content["count"] > 1): ?><sup><?php echo $content["count"] ?></sup><?php endif; ?></h3>
                    <form method="post">
                        <div class="form-group">
                            <textarea class="form-control" rows="1" name="content"><?php echo file_get_contents($contentFilePath) ?></textarea>
                        </div>
                        <input type="button" class="btn btn-default preview-modal" value="preview"/>
                        <input type="hidden" name="item" value="<?php echo $content["name"] ?>"/>
                        <input type="hidden" class="scroll" name="scroll" value="0"/>
                        <input type="submit" name="<?php echo $content["name"] ?>" value="save" class="btn btn-primary submit"/>
                    </form>
                </div>
            <?php endforeach; ?>
<!--            <hr/>-->
        </div>

    <?php endforeach; ?>

</div>

<?php require_once __DIR__ . "/footer.php"; ?>
