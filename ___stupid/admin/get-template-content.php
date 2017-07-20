<?php

/////////////////////////////////////////////////////////////////////////////
require_once __DIR__ . "/../libs/stupidBackend.php";
$stupidBackend = new stupidBackend();
$stupidBackend->lockPage();

/////////////////////////////////////////////////////////////////////////////
// Data collecting
/////////////////////////////////////////////////////////////////////////////
$templateID = @$_GET["template"];
if(isset($templateID) && strpos($templateID, "..") === false)
{
    $file = PAGE_TEMPLATES_PATH . "/" . $templateID;
    if(file_exists($file))
    {
        echo file_get_contents($file);
    }
    else
    {
        echo "";
    }
}

?>