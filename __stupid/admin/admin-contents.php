<?php 

/////////////////////////////////////////////////////////////////////////////
require_once __DIR__."/../libs/stupidBackend.php";
$stupidBackend = new stupidBackend();
$stupidBackend->lockPage();

/////////////////////////////////////////////////////////////////////////////
$contents = $stupidBackend->listContents();
$contentsByPages = $stupidBackend->listContentsByPages();

/////////////////////////////////////////////////////////////////////////////
$saved = false;
$itemSaved = "";
if(isset($_POST["item"])) {
    foreach ($contents as $contentName => $contentFiles) {
        if($contentName == $_POST["item"]) {
            $itemSaved = $stupidBackend->saveContent($contentName,$_POST["content"]);
            $saved = true;
            break;
        }
    }
}
if($saved) {
    $stupidBackend->stupid->clearCache();
    $contents = $stupidBackend->listContents();
    $contentsByPages = $stupidBackend->listContentsByPages();
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

    <?php ksort($contentsByPages);?>

    <nav>
        <ul>
            <?php foreach ($contentsByPages as $contentPage => $contentNames) :?>
                <li><a href="#<?php echo $contentPage?>"><?php echo $contentPage?><sup><?php echo count($contentNames)?></sup></a></li>
            <?php endforeach;?>
        </ul>
    </nav>
    <hr/>
    
    <?php foreach ($contentsByPages as $contentPage => $contentNames) :?>

        <a name="<?php echo $contentPage?>"></a>
        <h2><?php echo $contentPage?></h2>

        <?php foreach ($contentNames as $contentName):?>
            <?php $contentFilePath = $stupidBackend->stupid->getContentFilePath($contentName);?>
            <?php createFileIfNotExists($contentFilePath)?>
            <h3><?php echo $contentName?></h3>
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

        <hr/>
    <?php endforeach;?>

</div>

<?php require_once __DIR__."/footer.php";?>