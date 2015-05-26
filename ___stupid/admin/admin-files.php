<?php 

/////////////////////////////////////////////////////////////////////////////
require_once __DIR__."/../libs/stupidBackend.php";
$stupidBackend = new stupidBackend();
$stupidBackend->lockPage();

/////////////////////////////////////////////////////////////////////////////
$files = $stupidBackend->listFiles();
$filesByPages = $stupidBackend->listFilesByPages();

/////////////////////////////////////////////////////////////////////////////
$saved = false;
$itemSaved = "";
if(isset($_POST["item"])) {
    foreach ($files as $fileName => $fileFiles) { 
        if($fileName == $_POST["item"]) {
            $file = $_FILES["file"];
            $item = $_POST["item"];
            $itemSaved = $stupidBackend->saveFile($fileName, $file["tmp_name"]);
            $saved = true;
            break;
        }
    }
}

if($saved) {
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

    <?php ksort($filesByPages);?>

    <nav>
        <ul>
            <?php foreach ($filesByPages as $filePage => $fileNames) :?>
                <li><a href="#<?php echo $filePage?>"><?php echo $filePage?><sup><?php echo count($fileNames)?></sup></a></li>
            <?php endforeach;?>
        </ul>
    </nav>
    <hr/>

    <?php foreach ($filesByPages as $filePage => $fileNames) :?>

        <a name="<?php echo $filePage?>"></a>
        <h2><?php echo $filePage?></h2>

        <?php foreach ($fileNames as $fileName):?>
            <h3><?php echo $fileName?></h3>
            <div class="file">
                <form method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <img src="<?php $stupidBackend->stupid->__file($fileName)?>?ck=<?php echo time()?>" class="admin-file"/>
                    </div>
                    <span class="btn btn-default btn-file">Replace <input type="file" name="file" value="replace"/></span>
                    <input type="hidden" name="item" value="<?php echo $fileName?>"/>
                    <input type="submit" name="<?php echo $fileName?>" value="save" class="btn btn-primary submit"/>
                </form>
            </div>
        <?php endforeach;?>
    <?php endforeach;?>
</div>

<?php require_once __DIR__."/footer.php";?>