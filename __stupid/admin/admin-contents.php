<?php 

/////////////////////////////////////////////////////////////////////////////
require_once __DIR__."/../libs/stupidBackend.php";
$stupidBackend = new stupidBackend();
$stupidBackend->lockPage();

/////////////////////////////////////////////////////////////////////////////
$contents = $stupidBackend->listContents();

/////////////////////////////////////////////////////////////////////////////
$saved = false;
$itemSaved = "";
if(isset($_POST["item"])) {
    foreach ($contents as $contentName => $contentFiles) {
        if($contentName == $_POST["item"]) {
            file_put_contents($stupidBackend->stupid->getContentFilePath($contentName), $_POST["content"]);
            $itemSaved = $_POST["item"];
            $saved = true;
            break;
        }
    }
    $stupidBackend->stupid->clearCache();
}

/////////////////////////////////////////////////////////////////////////////
?>

<?php require_once __DIR__."/header.php";?>

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
    <?php if($saved):?>
        <div class="alert alert-success" role="alert"><?php echo $itemSaved?> <strong>saved</strong> !</div>
    <?php endif;?>

    <?php ksort($contents);?>
    <?php foreach ($contents as $contentName => $contentFiles) :?>
        <?php $contentFilePath = $stupidBackend->stupid->getContentFilePath($contentName);?>
        <?php createFileIfNotExists($contentFilePath)?>
        <h2><?php echo $contentName?> <small><?php echo implode($contentFiles,", ")?></small></h2>
        <div class="content">
            <form method="post">
                <div class="form-group">
                    <textarea class="form-control" rows="1" name="content"><?php echo file_get_contents($contentFilePath)?></textarea>
                </div>
                <input type="button" class="btn btn-default preview-modal" value="preview"/>
                <input type="hidden" name="item" value="<?php echo $contentName?>"/>
                <input type="submit" name="<?php echo $contentName?>" value="save" class="btn btn-primary submit"/>
            </form>
        </div>
    <?php endforeach;?>
</div>

<?php require_once __DIR__."/footer.php";?>