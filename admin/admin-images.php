<?php 

/////////////////////////////////////////////////////////////////////////////
header("Cache-Control: no-cache");
header("Pragma: no-cache");

/////////////////////////////////////////////////////////////////////////////
require_once __DIR__."/../libs/tools.php";
lockPage();

/////////////////////////////////////////////////////////////////////////////
$images = getImagesList();

/////////////////////////////////////////////////////////////////////////////
$saved = false;
$itemSaved = "";
if(isset($_POST["item"])) {
    foreach ($images as $imageName => $imageFiles) { 
        if($imageName == $_POST["item"]) {
            $file = $_FILES["file"];
            $item = $_POST["item"];
            move_uploaded_file($file["tmp_name"], getImagePath($item));
            $itemSaved = $item;
            $saved = true;
            break;
        }
    }
    clearSMTECache();
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

    <?php ksort($images);?>
    <?php foreach ($images as $imageName => $imageFiles) :?>
        <h2><?php echo $imageName?> <small><?php echo implode($imageFiles,", ")?></small></h2>
        <div class="image">
            <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <img src="../<?php echo renderImage($imageName)?>" class="admin-image"/>
                </div>
                <span class="btn btn-default btn-file">Replace <input type="file" name="file" value="replace"/></span>
                <input type="hidden" name="item" value="<?php echo $imageName?>"/>
                <input type="submit" name="<?php echo $imageName?>" value="save" class="btn btn-primary submit"/>
            </form>
        </div>
    <?php endforeach;?>
</div>