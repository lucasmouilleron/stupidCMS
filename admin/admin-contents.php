<?php 

/////////////////////////////////////////////////////////////////////////////
require_once __DIR__."/../libs/tools.php";
lockPage();

/////////////////////////////////////////////////////////////////////////////
$contents = getContentsList();

/////////////////////////////////////////////////////////////////////////////
$saved = false;
$itemSaved = "";
if(isset($_POST["item"])) {
    foreach ($contents as $contentName => $contentFiles) {
        if($contentName == $_POST["item"]) {
            file_put_contents(getMDFilePath($contentName), $_POST["content"]);
            $itemSaved = $_POST["item"];
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

    <div class="container">

        <header>
            <h1>Administration</h1>
            <?php require_once __DIR__."/menu.php";?>
        </header>

        <p>Markdown documentation : <a href="https://github.com/adam-p/markdown-here/wiki/Markdown-Cheatsheet" target="_blank">https://github.com/adam-p/markdown-here/wiki/Markdown-Cheatsheet</a></p>

        <?php if($saved):?>
            <div class="alert alert-success" role="alert"><?php echo $itemSaved?> <strong>saved</strong> !</div>
        <?php endif;?>

        <?php ksort($contents);?>
        <?php foreach ($contents as $contentName => $contentFiles) :?>
            <?php $contentFilePath = getMDFilePath($contentName);?>
            <?php createFileIfNotExists($contentFilePath)?>
            <h2><?php echo $contentName?> <small><?php echo implode($contentFiles,", ")?></small></h2>
            <form method="post">
                <div class="form-group">
                    <textarea class="form-control" rows="10" name="content"><?php echo file_get_contents($contentFilePath)?></textarea>
                </div>
                <input type="button" class="btn btn-default preview-modal" value="preview"/>
                <input type="hidden" name="item" value="<?php echo $contentName?>"/>
                <input type="submit" name="<?php echo $contentName?>" value="save" class="btn btn-primary"/>
            </form>
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