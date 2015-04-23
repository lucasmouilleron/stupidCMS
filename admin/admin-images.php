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

<!DOCTYPE html>
<html>
<head>
    <title><?php echo GENERAL_COMPANY?> | Admininistration</title>
    <meta name="description" content="">
    <meta name="robots" content="noindex">
    <meta charset="utf-8">
    <link rel="stylesheet" href="./css/main.css">
</head>
<body>


    <!-- /////////////////////////////////////////////////////////////// -->
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

    <!-- /////////////////////////////////////////////////////////////// -->
    <div class="container">

        <header>
            <h1>Administration</h1>
            <?php require_once __DIR__."/menu.php";?>
        </header>

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

        <footer>
            <p>&copy; <?php echo GENERAL_COMPANY ?> | <a href="mailto:<?php echo GENERAL_EMAIL?>"><?php echo GENERAL_EMAIL?></a></p>
        </footer>

    </div>

    <?php if(DEBUG_MODE) :?>
        <div class="debug">DEBUG : <?php echo getDebugInfos();?></div>
    <?php endif;?>

    <!-- /////////////////////////////////////////////////////////////// -->
    <script src="./js/jquery-1.11.2.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>
    <script src="./js/autosize.js"></script>
    <script src="./js/Markdown.Converter.js"></script>
    <script src="./js/main.js"></script>

</body>
</html>