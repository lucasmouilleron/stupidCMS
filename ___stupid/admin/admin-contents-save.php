<?php

header("Content-Type: application/json");

/////////////////////////////////////////////////////////////////////////////
require_once __DIR__ . "/../libs/stupidBackend.php";
$stupidBackend = new stupidBackend();
$stupidBackend->lockPage(false);

/////////////////////////////////////////////////////////////////////////////
$saved = false;
$success = false;
$hint = "Not saved";

$contents = $stupidBackend->listContents();
if(isset($_POST["item"]))
{
    foreach($contents as $contentName => $contentFiles)
    {
        if($contentName == $_POST["item"])
        {
            $itemSaved = $stupidBackend->saveContent($contentName, $_POST["content"]);
            $saved = true;
            break;
        }
    }
}
if($saved)
{
    $stupidBackend->stupid->clearCache();
    $stupidBackend->scanContents();
    $stupidBackend->scanFiles();
    $contents = $stupidBackend->listContents();
    $contentsByPages = $stupidBackend->listContentsByPages();
    $success = true;
}

header("HTTP/1.1 200");
echo json_encode(array("success" => $success, "hint" => $hint));

?>