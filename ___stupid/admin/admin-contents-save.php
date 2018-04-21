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

if(isset($_POST["item"]))
{
    $itemSaved = $stupidBackend->saveContent($_POST["item"], $_POST["content"]);
    $saved = true;
}
if($saved)
{
    $stupidBackend->stupid->clearCache();
    $stupidBackend->scanContents();
    $stupidBackend->scanFiles();
    $success = true;
}

header("HTTP/1.1 200");
echo json_encode(array("success" => $success, "hint" => $hint));

?>