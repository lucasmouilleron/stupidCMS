<?php

/////////////////////////////////////////////////////////////////////////////
require_once __DIR__ . "/../libs/stupidBackend.php";
$stupidBackend = new stupidBackend();
$stupidBackend->lockPage();

/////////////////////////////////////////////////////////////////////////////
// Data collecting
/////////////////////////////////////////////////////////////////////////////
$files = $stupidBackend->listFiles();
$filesByPages = $stupidBackend->listFilesByPages();

/////////////////////////////////////////////////////////////////////////////
// Form processing
/////////////////////////////////////////////////////////////////////////////
$saved = false;
$deleted = false;
$itemSaved = "";
$itemDeleted = "";
if(isset($_POST["item"]))
{
    if(array_key_exists("delete", $_POST))
    {
        $item = $_POST["item"];
        $itemDeleted = $stupidBackend->deleteFile($item);
        $deleted = true;
    }
    else
    {
        $file = $_FILES["file"];
        $itemSaved = $stupidBackend->saveFile($_POST["item"], $file["tmp_name"]);
        $saved = true;
    }
}
if($saved || $deleted)
{
    $stupidBackend->stupid->clearCache();
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

    <div id="sidebar" style="display: none">
        <div id="toc-toggle">&#x25BC;</div>
        <div id="toc" data-headers="h2"></div>
    </div>

    <div class="container">
        <?php if($saved): ?>
            <div class="alert alert-success" role="alert"><?php echo $itemSaved ?> <strong>saved</strong> !</div>
        <?php endif; ?>

        <?php if($deleted): ?>
            <div class="alert alert-success" role="alert"><?php echo $itemDeleted ?> <strong>delteted</strong> !</div>
        <?php endif; ?>

        <?php ksort($filesByPages); ?>

        <?php foreach($filesByPages as $filePage => $filess) : ?>

            <div class="file-page page-container">
                <a name="<?php echo $filePage ?>"></a>
                <h2><?php echo $filePage ?></h2>

                <?php foreach($filess as $file): ?>

                    <div class="file">
                        <a name="<?php echo $filePage . "/" . $file["name"] ?>"></a>
                        <h3><?php echo $file["name"] ?><?php if($file["count"] > 1): ?><sup><?php echo $file["count"] ?></sup><?php endif; ?></h3>
                        <form method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <?php if($stupidBackend->fileExists($file["name"])): ?>
                                    <?php if($stupidBackend->isFileAnImage($file["name"])): ?>
                                        <img src="<?php $stupidBackend->stupid->__file($file["name"]) ?>?ck=<?php echo time() ?>" class="admin-file"/>
                                    <?php else: ?>
                                        <a href="<?php $stupidBackend->stupid->__file($file["name"]) ?>?ck=<?php echo time() ?>" class="admin-file" data-url="<?php $stupidBackend->stupid->__file($file["name"]) ?>" target="_new"><?php $stupidBackend->stupid->__file($file["name"]) ?></a>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <?php if($stupidBackend->isFileAnImage($file["name"])): ?>
                                        <img class="admin-file admin-file-empty"/>
                                    <?php else: ?>
                                        <a href="<?php $stupidBackend->stupid->__file($file["name"]) ?>?ck=<?php echo time() ?>" data-url="<?php $stupidBackend->stupid->__file($file["name"]) ?>" class="admin-file" target="_new"></a>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                            <span class="btn btn-warning btn-file">Add / Replace <input type="file" name="file" value="replace"/></span>
                            <input type="hidden" name="item" value="<?php echo $file["name"] ?>"/>
                            <input type="submit" name="<?php echo $file["name"] ?>" value="save" class="btn btn-primary submit"/>
                            <button type="submit" name="delete" class="btn btn-danger submit-delete">Delete</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>

<?php require_once __DIR__ . "/footer.php"; ?>