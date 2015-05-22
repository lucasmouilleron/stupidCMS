<?php 

/////////////////////////////////////////////////////////////////////////////
require_once __DIR__."/../libs/stupidBackend.php";
$stupidBackend = new stupidBackend();
$stupidBackend->lockPage();

/////////////////////////////////////////////////////////////////////////////
$images = $stupidBackend->listImages();
$imagesByPages = $stupidBackend->listImagesByPages();

/////////////////////////////////////////////////////////////////////////////
$saved = false;
$itemSaved = "";
if(isset($_POST["item"])) {
    foreach ($images as $imageName => $imageFiles) { 
        if($imageName == $_POST["item"]) {
            $file = $_FILES["file"];
            $item = $_POST["item"];
            move_uploaded_file($file["tmp_name"], $stupidBackend->stupid->getImagePath($item));
            $itemSaved = $item;
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

    <?php ksort($imagesByPages);?>

    <nav>
        <ul>
            <?php foreach ($imagesByPages as $imagePage => $imageNames) :?>
                <li><a href="#<?php echo $imagePage?>"><?php echo $imagePage?></a></li>
            <?php endforeach;?>
        </ul>
    </nav>
    <hr/>

    <?php foreach ($imagesByPages as $imagePage => $imageNames) :?>

        <a name="<?php echo $imagePage?>"></a>
        <h2><?php echo $imagePage?></h2>

        <?php foreach ($imageNames as $imageName):?>
            <h3><?php echo $imageName?></h3>
            <div class="image">
                <form method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <img src="<?php $stupidBackend->stupid->__img($imageName)?>?ck=<?php echo time()?>" class="admin-image"/>
                    </div>
                    <span class="btn btn-default btn-file">Replace <input type="file" name="file" value="replace"/></span>
                    <input type="hidden" name="item" value="<?php echo $imageName?>"/>
                    <input type="submit" name="<?php echo $imageName?>" value="save" class="btn btn-primary submit"/>
                </form>
            </div>
        <?php endforeach;?>
    <?php endforeach;?>
</div>

<?php require_once __DIR__."/footer.php";?>