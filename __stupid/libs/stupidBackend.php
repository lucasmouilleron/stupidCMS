<?php

/////////////////////////////////////////////////////////////////////////////
require_once __DIR__."/helpers.php";
require_once __DIR__."/stupidConfig.php";
require_once __DIR__."/stupidDefinitions.php";
require_once __DIR__."/stupidEngine.php";

///////////////////////////////////////////////////////////////////////////////
class StupidBackend
{

    ///////////////////////////////////////////////////////////////////////////////
    public $stupid;

    ///////////////////////////////////////////////////////////////////////////////
    function __construct() {
        $this->stupid = new Stupid();
    }

    /////////////////////////////////////////////////////////////////////////////
    function listPagesFull() {
        $files = getDirContents(PAGES_PATH);
        $pages = array();
        foreach ($files as $file) {
            if(endsWith($file, PAGES_EXTENSION) && !startsWith($file,SMTE_CACHE_PATH)) {
                array_push($pages, str_replace(PAGES_EXTENSION, "", $file));
            }
        }
        return $pages;
    }

    /////////////////////////////////////////////////////////////////////////////
    function getContentsList() {
        $contents = @json_decode(file_get_contents(CONTENTS_FILE), true);
        if($contents === null) {
            return array();
        }
        else {
            return $contents;
        }
    }

    /////////////////////////////////////////////////////////////////////////////
    function getImagesList() {
        $images = @json_decode(file_get_contents(IMAGES_FILE), true);
        if($images === null) {
            return array();
        }
        else {
            return $images;
        }
    }

    ///////////////////////////////////////////////////////////////////////////////
    function isAuthentified() {
        if(!isset($_SESSION["authentified"])) {
            return false;
        }
        else {
            return $_SESSION["authentified"];
        }
    }

    ///////////////////////////////////////////////////////////////////////////////
    function lockPage() {
        if(!$this->isAuthentified()) {
            header("Location: login");
        }
    }

    ///////////////////////////////////////////////////////////////////////////////
    function login($password) {
        if($password == ADMIN_PASSWORD) {
            $_SESSION["authentified"] = true;
            header("Location: .");   
        }
    }

}


?>