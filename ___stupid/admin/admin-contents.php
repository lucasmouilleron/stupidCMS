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
// Form processing
/////////////////////////////////////////////////////////////////////////////
$saved = false;
$itemSaved = "";
if(isset($_POST["item"]))
{
    foreach($contents as $contentName => $contentFiles)
    {
        if($contentName == $_POST["item"])
        {
            $itemSaved = $stupidBackend->saveContent($contentName, $_POST["content"]);
            $saved = true;
            break;
        }
    }
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

    <div class="container">
        <?php if($saved): ?>
            <div class="alert alert-success" role="alert"><?php echo $itemSaved ?> <strong>saved</strong> !</div>
        <?php endif; ?>

        <?php ksort($contentsByPages); ?>

        <nav class="contents">
            <ul>
                <?php foreach($contentsByPages as $contentPage => $contents) : ?>
                    <li><a href="#<?php echo $contentPage ?>"><?php echo $contentPage ?><sup><?php echo count($contents) ?></sup></a></li>
                <?php endforeach; ?>
            </ul>
        </nav>
        <hr/>

        <?php foreach($contentsByPages as $contentPage => $contents) : ?>

            <a name="<?php echo $contentPage ?>"></a>
            <h2><?php echo $contentPage ?></h2>

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
                        <input type="submit" name="<?php echo $content["name"] ?>" value="save" class="btn btn-primary submit"/>
                    </form>
                </div>
            <?php endforeach; ?>

            <hr/>
        <?php endforeach; ?>

    </div>

<?php require_once __DIR__ . "/footer.php"; ?>