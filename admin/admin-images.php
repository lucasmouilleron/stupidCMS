<?php 
/////////////////////////////////////////////////////////////////////////////
header("Cache-Control: no-cache");
header("Pragma: no-cache");

/////////////////////////////////////////////////////////////////////////////
require_once __DIR__."/../libs/tools.php";
$images = getImagesList();

/////////////////////////////////////////////////////////////////////////////
$saved = false;
$itemSaved = "";
if(isset($_POST["item"])) {
    for ($i=0; $i < count($images); $i++) { 
        if($images[$i] == $_POST["item"]) {
            $file = $_FILES["file"];
            $item = $_POST["item"];
            move_uploaded_file($file["tmp_name"], getImagePath($item));
            $itemSaved = $item;
            $saved = true;
            break;
        }
    }
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
            <nav>
                <a href="admin-contents">Contents</a> <span class="sep">|</span> <a href="admin-images" class="active">Images</a> <span class="sep">|</span> <a href="scan">Scan pages</a>
            </nav>
        </header>

        <?php if($saved):?>
            <div class="alert alert-success" role="alert"><?php echo $itemSaved?> <strong>saved</strong> !</div>
        <?php endif;?>

        <?php sort($images);?>
        <?php for ($i=0; $i < count($images); $i++) :?>
            <?php $imageName = $images[$i];?>
            <h2><?php echo $imageName?></h2>
            <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                <img src="../<?php _img($imageName)?>" class="admin-image"/>
                </div>
                <span class="btn btn-default btn-file">Replace <input type="file" name="file" value="replace"/></span>
                <input type="hidden" name="item" value="<?php echo $imageName?>"/>
                <input type="submit" name="<?php echo $imageName?>" value="save" class="btn btn-primary"/>
            </form>

        <?php endfor;?>

        <footer>
            <p>&copy; <?php echo GENERAL_COMPANY ?> | <a href="mailto:<?php echo GENERAL_EMAIL?>"><?php echo GENERAL_EMAIL?></a></p>
        </footer>

    </div>

    <!-- /////////////////////////////////////////////////////////////// -->
    <script src="./js/jquery-1.11.2.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>
    <script src="./js/Markdown.Converter.js"></script>
    <script src="./js/main.js"></script>

</body>
</html>