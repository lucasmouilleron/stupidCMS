<?php

/////////////////////////////////////////////////////////////////////////////
require_once __DIR__."/helpers.php";
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

    ///////////////////////////////////////////////////////////////////////////////
    function scanImages() {
        $images = array();
        $pages = $this->listPages();
        foreach ($pages as $page) {
            $content = file_get_contents($page.PAGES_EXTENSION);
            $page = str_replace(PAGES_PATH, "", $page);
            preg_match_all("/\{\{".IMAGE_TAG."(.*)\}\}/U",$content, $matches);
            $results = $matches[1];
            foreach ($results as $result) {
                if(!array_key_exists($result, $images)) {
                    $images[$result] = array(); 
                }
                array_push($images[$result], $page);
            }
        }

        file_put_contents(IMAGES_FILE, json_encode($images));
        return $images;
    }

    ///////////////////////////////////////////////////////////////////////////////
    function scanContents() {
        $contents = array();
        $pages = $this->listPages();
        foreach ($pages as $page) {
            $content = file_get_contents($page.PAGES_EXTENSION);
            $page = str_replace(PAGES_PATH, "", $page);
            preg_match_all("/\{\{".CONTENT_TAG."(.*)\}\}/U",$content, $matches);
            $results = $matches[1];
            foreach ($results as $result) {
                if(!array_key_exists($result, $contents)) {
                    $contents[$result] = array();   
                }
                array_push($contents[$result], $page);
            }
        }
        file_put_contents(CONTENTS_FILE, json_encode($contents));
        return $contents;
    }

    /////////////////////////////////////////////////////////////////////////////
    function listPages() {
        $files = getDirContents(PAGES_PATH);
        $pages = array();
        foreach ($files as $file) {
            if(endsWith($file, PAGES_EXTENSION) && !startsWith($file,STUPID_PATH)) {
                array_push($pages, str_replace(PAGES_EXTENSION, "", $file));
            }
        }
        return $pages;
    }

    /////////////////////////////////////////////////////////////////////////////
    function listContents() {
        $contents = @json_decode(file_get_contents(CONTENTS_FILE), true);
        if($contents === null) {
            return array();
        }
        else {
            return $contents;
        }
    }

    /////////////////////////////////////////////////////////////////////////////
    function listImages() {
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