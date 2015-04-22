<?php 

/////////////////////////////////////////////////////////////////////////////
require_once __DIR__."/libs/tools.php";

/////////////////////////////////////////////////////////////////////////////
$saved = false;
$itemSaved = "";
if(isset($_POST["item"])) {
    for ($i=0; $i < count($contents); $i++) { 
        if($contents[$i] == $_POST["item"]) {
            file_put_contents(getMDFilePath($contents[$i]), $_POST["content"]);
            $itemSaved = $_POST["item"];
            $saved = true;
            break;
        }
    }
}

/////////////////////////////////////////////////////////////////////////////
?>


<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>

    <title><?php echo GENERAL_COMPANY?> | Admininistration</title>
    <meta name="description" content="">
    <meta name="robots" content="noindex">

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width initial-scale=1.0">
    <link rel="shortcut icon" href="assets/img/favicon.png">
    <link rel="stylesheet" href="assets/css/main.css">

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

        <div class="jumbotron">
            <h1>Administration</h1>
            <p>Don't fuck it up</p>
        </div>

        <?php if($saved):?>
            <div class="alert alert-success" role="alert"><?php echo $itemSaved?> <strong>saved</strong> !</div>
        <?php endif;?>

        <?php sort($contents);?>
        <?php for ($i=0; $i < count($contents); $i++) :?>
            <?php $contentName = $contents[$i];?>
            <?php $contentFilePath = getMDFilePath($contents[$i]);?>
            <?php createFileIfNotExists($contentFilePath)?>
            <h2><?php echo $contents[$i]?></h2>
            <form method="post">
                <div class="form-group">
                    <textarea class="form-control" rows="10" name="content"><?php echo file_get_contents($contentFilePath)?></textarea>
                </div>
                <input type="button" class="btn btn-default preview-modal" value="preview"/>
                <input type="hidden" name="item" value="<?php echo $contentName?>"/>
                <input type="submit" name="<?php echo $contentName?>" value="save" class="btn btn-primary"/>
            </form>

        <?php endfor;?>

    <footer>
        <p>&copy; <?php echo GENERAL_COMPANY ?> | <a href="mailto:<?php echo GENERAL_EMAIL?>"><?php echo GENERAL_EMAIL?></a></p>
    </footer>

</div>

<!-- /////////////////////////////////////////////////////////////// -->
<script data-main="assets/js/scripts.min" src="assets/js/require.js"></script>

</body>
</html>