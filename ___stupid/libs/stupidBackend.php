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
    function scanFiles() {

        function enrichiFoundFiles($content,$files,$page) {
            preg_match_all("/\{\{".FILE_TAG."(.*)\}\}/U",$content, $matches);
            $results = $matches[1];
            foreach ($results as $result) {
                if(!array_key_exists($result, $files)) {
                    $files[$result] = array(); 
                }
                if(!in_array($page, $files[$result])) {
                    array_push($files[$result], $page);
                }
            }
            return $files;
        }

        @mkdir(FILES_PATH);
        $files = array();
        $pages = $this->listPagesFullPath();
        foreach ($pages as $page) {
            $content = file_get_contents($page.PAGES_EXTENSION);
            $page = str_replace(PAGES_PATH, "", $page);
            $files = enrichiFoundFiles($content,$files,$page);
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
                $files = enrichiFoundFiles($content,$files,$page);
            }
        }

        file_put_contents(FILES_FILE, json_encode($files));
        return $files;
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

        @mkdir(CONTENTS_PATH);
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
    function saveFile($fileName, $file) {
        $filePath = $this->stupid->getFilePath($fileName);
        move_uploaded_file($file, $filePath);
        return $filePath;
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
    function listFiles() {
        $files = @json_decode(file_get_contents(FILES_FILE), true);
        if($files === null) {
            return array();
        }
        else {
            return $files;
        }
    }

    /////////////////////////////////////////////////////////////////////////////
    function listFilesByPages() {
        $files = $this->listFiles();
        $filesByPage = array();
        foreach ($files as $fileName => $filePages) {
            if(count($filePages)>1) {
                $filePage = "__multiple";
            }
            else {
                $filePage = $filePages[0];
            }
            if(!array_key_exists($filePage, $filesByPage)) {
                $filesByPage[$filePage] = array();
            }
            array_push($filesByPage[$filePage], $fileName);
        }
        return $filesByPage;
    }

    ///////////////////////////////////////////////////////////////////////////////
    function cleanContents() {
        $deletedContents = array();
        $this->scanContents();
        $contents = $this->stupid->listContents(true);
        array_push($contents, CONTENTS_FILE);
        $contentFiles = getDirContents(CONTENTS_PATH);
        foreach ($contentFiles as $contentFile) {
            if(!in_array($contentFile, $contents)) {
                array_push($deletedContents, $contentFile);
                unlink($contentFile);
            }
        }
        return $deletedContents;
    }

    ///////////////////////////////////////////////////////////////////////////////
    function cleanFiles() {
        $deletedFiles = array();
        $this->scanFiles();
        $files = $this->stupid->listFiles(true);
        array_push($files, FILES_FILE);
        $filesFiles = getDirContents(FILES_PATH);
        foreach ($filesFiles as $fileFile) {
            if(!in_array($fileFile, $files)) {
                array_push($deletedFiles, $fileFile);
                unlink($fileFile);
            }
        }
        return $deletedFiles;
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