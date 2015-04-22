<?php 

/////////////////////////////////////////////////////////////////////////////
require_once __DIR__."/../libs/tools.php";
$contents = getContentsList();

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
                <a href="admin-contents" class="active">Contents</a> <span class="sep">|</span> <a href="admin-images">Images</a> <span class="sep">|</span> <a href="scan">Scan pages</a>
            </nav>
        </header>

        <p>Markdown documentation : <a href="https://github.com/adam-p/markdown-here/wiki/Markdown-Cheatsheet" target="_blank">https://github.com/adam-p/markdown-here/wiki/Markdown-Cheatsheet</a></p>

        <?php if($saved):?>
            <div class="alert alert-success" role="alert"><?php echo $itemSaved?> <strong>saved</strong> !</div>
        <?php endif;?>

        <?php sort($contents);?>
        <?php for ($i=0; $i < count($contents); $i++) :?>
            <?php $contentName = $contents[$i];?>
            <?php $contentFilePath = getMDFilePath($contentName);?>
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
    <script src="./js/jquery-1.11.2.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>
    <script src="./js/Markdown.Converter.js"></script>
    <script src="./js/main.js"></script>

</body>
</html>