<?php

/////////////////////////////////////////////////////////////////////////////
require_once __DIR__ . "/../libs/stupidBackend.php";

/////////////////////////////////////////////////////////////////////////////
$saved = false;
$success = false;
$hint = "Not saved";
header("Content-Type: application/json");

try
{
    $stupidBackend = new stupidBackend();
    $stupidBackend->lockPage(false);
    if(isset($_POST["item"]))
    {
        $itemSaved = $stupidBackend->savePageFullPath($_POST["item"], $_POST["content"]);
        $saved = true;
    }
    if($saved)
    {
        $stupidBackend->stupid->clearCache();
        $stupidBackend->scanContents();
        $stupidBackend->scanFiles();
        $success = true;
    }
}
catch(Exception $e)
{
    $hint = $e->getMessage();
}

header("HTTP/1.1 200");
echo json_encode(array("success" => $success, "hint" => $hint));

?>