<?php 

/////////////////////////////////////////////////////////////////////////////
require_once __DIR__."/../libs/stupidBackend.php";
$stupidBackend = new stupidBackend();
$stupidBackend->lockPage();

/////////////////////////////////////////////////////////////////////////////
$pages = $stupidBackend->listPagesWithExtensions();
$templates = $stupidBackend->listTemplates();

/////////////////////////////////////////////////////////////////////////////
$saved = false;
$itemSaved = "";
if(isset($_POST["item"])) {
    foreach ($pages as $page) {
        if($page == $_POST["item"]) {
            $itemSaved = $stupidBackend->savePageFullPath($page,$_POST["content"]);
            $saved = true;
            break;
        }
    }
}
if(isset($_POST["addPage"])) {
    $itemSaved = $stupidBackend->savePage($_POST["name"],$_POST["content"]);
    $saved = true;
}
if($saved) {
    $stupidBackend->stupid->clearCache();
    $pages = $stupidBackend->listPagesWithExtensions();
}

ksort($pages);
ksort($templates);

/////////////////////////////////////////////////////////////////////////////
?>


<?php require_once __DIR__."/header.php";?>

<div class="container">

    <?php if($saved):?>
        <div class="alert alert-success" role="alert"><?php echo $itemSaved?> <strong>saved</strong> !</div>
    <?php endif;?>

    <h2>Add a page</h2>
    <div class="addPage">
        <form method="post" data-toggle="validator">
            <div class="form-group">
                <input type="text" class="form-control" id="name" name="name" placeHolder="Page name" required>
            </div>
            <div class="next">
                <hr/>
                <div class="form-group">
                    <select class="form-control" id="template">
                        <option value="">Start from a template</option>
                        <?php foreach ($templates as $templateInfos):?>
                            <?php $templateName = str_replace(PAGE_TEMPLATES_PATH, "", $templateInfos["file"])?>
                            <option value="<?php echo $templateName?>"><?php echo $templateName?></option>
                        <?php endforeach;?>
                    </select>
                </div>

                <div class="form-group">
                    <textarea class="form-control" rows="4" id="content" name="content" placeHolder="Page content" required></textarea>
                </div>
                <input type="submit" name="addPage" value="save" class="btn btn-primary submit"/> 
            </div>
        </form>
    </div>

    <h2>Pages<sup><?php echo count($pages)?></sup></h2>
    <?php foreach ($pages as $page) :?>
        <?php $pageName = str_replace(PAGES_PATH, "", $page);?>
        <h3><?php echo $pageName;?></h3>
        <div class="page">
            <form method="post">
                <div class="form-group">
                    <textarea class="form-control" rows="1" name="content"><?php echo file_get_contents($page)?></textarea>
                </div>
                <input type="hidden" name="item" value="<?php echo $page?>"/>
                <input type="submit" name="<?php echo $page?>" value="save" class="btn btn-primary submit"/>
            </form>
        </div>
    <?php endforeach;?>
</div>

<?php require_once __DIR__."/footer.php";?>