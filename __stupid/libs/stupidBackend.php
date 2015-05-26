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

        function enrichiFoundImages($content,$images,$page) {
            preg_match_all("/\{\{".IMAGE_TAG."(.*)\}\}/U",$content, $matches);
            $results = $matches[1];
            foreach ($results as $result) {
                if(!array_key_exists($result, $images)) {
                    $images[$result] = array(); 
                }
                if(!in_array($page, $images[$result])) {
                    array_push($images[$result], $page);
                }
            }
            return $images;
        }

        $images = array();
        $pages = $this->listPagesFullPath();
        foreach ($pages as $page) {
            $content = file_get_contents($page.PAGES_EXTENSION);
            $page = str_replace(PAGES_PATH, "", $page);
            $images = enrichiFoundImages($content,$images,$page);
        }
        $contents = json_decode(file_get_contents(CONTENTS_FILE));
        foreach ($contents as $contentName => $contentPages) {
            $contentFile = $this->stupid->getContentFilePath($this->stupid->cleanContentName($contentName));
            if(file_exists($contentFile)) {
                $page = MULTIPLE_PAGE;
                if(count($contentPages) == 1) {
                    $page = $contentPages[0];
                }
                $content = file_get_contents($this->stupid->getContentFilePath($this->stupid->cleanContentName($contentName)));
                $images = enrichiFoundImages($content,$images,$page);
            }
        }

        file_put_contents(IMAGES_FILE, json_encode($images));
        return $images;
    }

    ///////////////////////////////////////////////////////////////////////////////
    function scanContents() {

        function enrichiFoundContents($content,$contents,$page) {
            preg_match_all("/\{\{".CONTENT_TAG."(.*)\}\}/U",$content, $matches);
            $results = $matches[1];
            foreach ($results as $result) {
                if(!array_key_exists($result, $contents)) {
                    $contents[$result] = array();   
                }
                if(!in_array($page, $contents[$result])) {
                    array_push($contents[$result], $page);
                }
            }
            return $contents;
        }

        $contents = array();
        $pages = $this->listPagesFullPath();
        foreach ($pages as $page) {
            $content = file_get_contents($page.PAGES_EXTENSION);
            $page = str_replace(PAGES_PATH, "", $page);
            $contents = enrichiFoundContents($content,$contents,$page);
        }
        foreach ($contents as $contentName => $contentPages) {
            $contentFile = $this->stupid->getContentFilePath($this->stupid->cleanContentName($contentName));
            if(file_exists($contentFile)) {
                $content = file_get_contents($this->stupid->getContentFilePath($this->stupid->cleanContentName($contentName)));
                $page = MULTIPLE_PAGE;
                if(count($contentPages) == 1) {
                    $page = $contentPages[0];
                }
                $contents = enrichiFoundContents($content,$contents,$page);
            }
        }

        file_put_contents(CONTENTS_FILE, json_encode($contents));
        return $contents;
    }

    /////////////////////////////////////////////////////////////////////////////
    function listPagesFullPath() {
        $pages = $this->stupid->listPages();
        $pagesFullPath = array();
        foreach ($pages as $page) {
            array_push($pagesFullPath, PAGES_PATH."/".$page);
        }
        return $pagesFullPath;
    }

    /////////////////////////////////////////////////////////////////////////////
    function listPagesWithExtensions() {
        $files = getDirContents(PAGES_PATH);
        $pages = array();
        foreach ($files as $file) {
            if(endsWith($file, PAGES_EXTENSION) && !startsWith($file,STUPID_PATH) &&  !startsWith($file,PAGE_TEMPLATES_PATH)) {
                array_push($pages, $file);
            }
        }
        return $pages;
    }

    /////////////////////////////////////////////////////////////////////////////
    function savePageFullPath($page, $content) {
        file_put_contents($page, $content);
        return $page;
    }

    /////////////////////////////////////////////////////////////////////////////
    function savePage($pageName, $content) {
        $pagePath = PAGES_PATH."/".$this->stupid->cleanPageNameFile($pageName);
        file_put_contents($pagePath, $content);
        return $pagePath;
    }

    /////////////////////////////////////////////////////////////////////////////
    function saveContent($contentName, $content) {
        $contentPath = $this->stupid->getContentFilePath($contentName);
        file_put_contents($contentPath, $content);
        return $contentPath;
    }

    /////////////////////////////////////////////////////////////////////////////
    function saveImage($imageName, $file) {
        $imagePath = $this->stupid->getImagePath($imageName);
        move_uploaded_file($file, $imagePath);
        return $imagePath;
    }

    /////////////////////////////////////////////////////////////////////////////
    function listTemplates() {
        $files = getDirContents(PAGE_TEMPLATES_PATH);
        $templates = array();
        foreach ($files as $file) {
            array_push($templates, array("file"=>$file,"content"=>file_get_contents($file)));
        }
        return $templates;
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
    function listContentsByPages() {
        $contents = $this->listContents();
        $contentsByPage = array();
        foreach ($contents as $contentName => $contentPages) {
            if(count($contentPages)>1) {
                $contentPage = MULTIPLE_PAGE;
            }
            else {
                $contentPage = $contentPages[0];
            }
            if(!array_key_exists($contentPage, $contentsByPage)) {
                $contentsByPage[$contentPage] = array();
            }
            array_push($contentsByPage[$contentPage], $contentName);
        }
        return $contentsByPage;
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

    /////////////////////////////////////////////////////////////////////////////
    function listImagesByPages() {
        $images = $this->listImages();
        $imagesByPage = array();
        foreach ($images as $imageName => $imagePages) {
            if(count($imagePages)>1) {
                $imagePage = "__multiple";
            }
            else {
                $imagePage = $imagePages[0];
            }
            if(!array_key_exists($imagePage, $imagesByPage)) {
                $imagesByPage[$imagePage] = array();
            }
            array_push($imagesByPage[$imagePage], $imageName);
        }
        return $imagesByPage;
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