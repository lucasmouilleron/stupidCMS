<?php

/////////////////////////////////////////////////////////////////////////////
require_once __DIR__ . "/../libs/stupidBackend.php";

/////////////////////////////////////////////////////////////////////////////
$saved = false;
$success = false;
$hint = "Not saved";
header("Content-Type: application/json");

/////////////////////////////////////////////////////////////////////////////
try
{
    $stupidBackend = new stupidBackend();
    $stupidBackend->lockPage(false);
    $hint = $_POST["item"];
    if(isset($_POST["item"]))
    {
        // delete
        if(array_key_exists("delete", $_POST))
        {
            $itemDeleted = $stupidBackend->deleteFile($_POST["item"]);
            $saved = true;
        }
        // save
        else if(array_key_exists("file", $_FILES))
        {
            $file = $_FILES["file"];
            $itemSaved = $stupidBackend->saveFile($_POST["item"], $file["tmp_name"]);
            $saved = true;
        }
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