<?php

/////////////////////////////////////////////////////////////////////////////
require_once __DIR__."/helpers.php";
require_once __DIR__."/stupidConfig.php";
require_once __DIR__."/stupidDefinitions.php";
require_once __DIR__."/stupidCacheFile.php";
require_once __DIR__."/stupidCacheRedis.php";

///////////////////////////////////////////////////////////////////////////////
class Stupid
{
    ///////////////////////////////////////////////////////////////////////////////
    public $debugInfos = array();
    public $cacheEngine;

    ///////////////////////////////////////////////////////////////////////////////
    function __construct() {
        if(SMTE_CACHE_ENGINE == "redis") {
            $this->cacheEngine = new StupidCacheRedis(SMTE_CACHE_REDIS_PORT);    
            $this->setDegubInfo("cacheEngine","redis");
        }
        else if(SMTE_CACHE_ENGINE == "file") {
            $this->cacheEngine = new StupidCacheFile(SMTE_CACHE_FILE_PATH);
            $this->setDegubInfo("cacheEngine","file");
        }
        else {
            $this->cacheEngine = new StupidCache();
            $this->setDegubInfo("cacheEngine","none");   
        }
    }

    /////////////////////////////////////////////////////////////////////////////
    function listPages() {
        $files = getDirContents(PAGES_PATH);
        $pages = array();
        foreach ($files as $file) {
            if(endsWith($file, PAGES_EXTENSION) && !startsWith($file,STUPID_PATH)) {
                array_push($pages, $this->cleanPageName(str_replace(PAGES_PATH, "", str_replace(PAGES_EXTENSION, "", $file))));
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
            $contentsArray = array();
            foreach ($contents as $contentName => $content) {
                array_push($contentsArray, $contentName);
            }
            return $contentsArray;
        }
    }

    ///////////////////////////////////////////////////////////////////////////////
    function clearCache() {
        $this->cacheEngine->clearCache();
        $pages = $this->listPages();
        foreach ($pages as $page) {
            $this->cacheEngine->setToCache($page,$this->renderPage($page, true));
            $this->setDegubInfo("pageCacheGenerated",$page);
        }
        $contents = $this->listContents();
        foreach ($contents as $contentName) {
            $contentName = $this->cleanContentName($contentName);
            $this->cacheEngine->setToCache(SMTE_CACHE_CONTENT_PREFIX.$contentName,$this->renderContent($contentName, true));
            $this->setDegubInfo("contentCacheGenerated",$contentName);
        }
        return array("pages"=>$pages, "contents"=>$contents);
    }

    ///////////////////////////////////////////////////////////////////////////////
    function processPage($page) {
        $page = $this->cleanPageName($page);
        if($this->isPageDynamic($page)) {
            include(PAGES_PATH."/".$page.".php");
        }
        else if($this->isPageRenderable($page)){ 
            eval(@$this->renderPage($page));
        }
    }

    ///////////////////////////////////////////////////////////////////////////////
    function isPageDynamic($page) {
        return file_exists(PAGES_PATH."/".$page.".php");
    }

    ///////////////////////////////////////////////////////////////////////////////
    function isPageRenderable($page) {
        if(!isset($page) || $page == "") {
            return false;
        }
        else {
            return true;
        }
    }

    ///////////////////////////////////////////////////////////////////////////////
    function renderPage($page, $noCache=false) {
        if($noCache == false && $this->cacheEngine->isInCache($page)) {
            $this->setDegubInfo("pageLoadedFromCache",$page);
            return $this->cacheEngine->getFromCache($page);
        }
        else {
            $this->setDegubInfo("pageLoadedFromFile",$page);
            ob_start(); 
            $content = @file_get_contents(PAGES_PATH."/".$page.PAGES_EXTENSION);
            if($content == "") {
                echo "404 !";
                exit();
            }
            return $this->renderSMTETemplate($content,$noCache);
        }
    }

    ///////////////////////////////////////////////////////////////////////////////
    function renderSMTETemplate($content, $noCache=false) {
        $content = addslashes($content);
        $content = preg_replace_callback("/\{\{(.*)\}\}/U", function($matches) {
            global $noCache;
            $result = $matches[1];
            if(startsWith($result,INCLUDE_TAG)) {
                $result = END_ECHO.$this->renderInclusion(substr($result, strlen(DEFINITION_TAG)), $noCache).BEGIN_ECHO;
            }
            if(startsWith($result,CONTENT_TAG)) {
                $result = END_ECHO.$this->renderContent(substr($result, strlen(CONTENT_TAG)), $noCache).BEGIN_ECHO;
            }
            if(startsWith($result,IMAGE_TAG)) {
                $result = END_ECHO.$this->renderImage(substr($result, strlen(IMAGE_TAG))).BEGIN_ECHO;
            }
            if(startsWith($result,DEFINITION_TAG)) {
                $result = END_ECHO.$this->renderDefinition(substr($result, strlen(DEFINITION_TAG))).BEGIN_ECHO;
            }
            if(startsWith($result,IF_TAG)) {
                $result = END_ECHO."if (".(stripslashes(substr($result, strlen(IF_TAG))))."){".BEGIN_ECHO;
            }
            if(startsWith($result,END_IF_TAG)) {
                $result = END_ECHO."};".BEGIN_ECHO;
            }
            return $result;
        }, $content);
        return BEGIN_ECHO.$content.END_ECHO;
        //return $content;
    }

    ///////////////////////////////////////////////////////////////////////////////
    function renderDefinition($def) {
        return BEGIN_ECHO.addslashes(constant($def)).END_ECHO;
    }

    ///////////////////////////////////////////////////////////////////////////////
    function renderInclusion($inclusionName, $noCache=false) {
        return $this->renderPage($inclusionName, $noCache);
    }

    ///////////////////////////////////////////////////////////////////////////////
    function renderContent($contentName, $noCache=false) {
        if($noCache == false && $this->cacheEngine->isInCache(SMTE_CACHE_CONTENT_PREFIX.$contentName)) {
            $this->setDegubInfo("contentLoadedFromCache",$contentName);
            return $this->cacheEngine->getFromCache(SMTE_CACHE_CONTENT_PREFIX.$contentName);
        }
        else {
            $this->setDegubInfo("contentLoadedFromFile",$contentName);
            $content = @file_get_contents($this->getContentFilePath($this->cleanContentName($contentName)));
            if(startsWith($content,CONTENT_MARKDOWN_PREFIX)) {
                $content = markdownToHTML(substr($content, strlen(CONTENT_MARKDOWN_PREFIX)));
            }
            else {
                $content = $this->defaultContentProcessing($content);
            }
            return $this->renderSMTETemplate($content);
        }
    }

    /////////////////////////////////////////////////////////////////////////////
    function renderImage($image) {
        return BEGIN_ECHO.addslashes(IMG_URL."/".$this->cleanImageName($image)).END_ECHO;
    }

    ///////////////////////////////////////////////////////////////////////////////
    function __inc($inclusionName) {
        eval($this->renderInclusion($inclusionName));
    }

    ///////////////////////////////////////////////////////////////////////////////
    function __cnt($contentName) {
        eval($this->renderContent($contentName));
    }

    ///////////////////////////////////////////////////////////////////////////////
    function __img($image) {
        eval($this->renderImage($image));
    }

    ///////////////////////////////////////////////////////////////////////////////
    function __def($def) {
        eval($this->renderDefinition($def));
    }

    /////////////////////////////////////////////////////////////////////////////
    function cleanContentName($contentName) {
        return ltrim(preg_replace(array("/\s/", "/\.[\.]+/", "/[^\w_\.\-]/"), array('_', '.', ''), $contentName),"/");
    }

    /////////////////////////////////////////////////////////////////////////////
    function cleanImageName($imageName) {
        return preg_replace(array('/\s/', '/\.[\.]+/', '/[^\w_\.\-]/'), array('_', '.', ''), $imageName);
    }

    /////////////////////////////////////////////////////////////////////////////
    function cleanPageName($page) {
        return ltrim($page,"/");
    }

    /////////////////////////////////////////////////////////////////////////////
    function getContentFilePath($contentName) {
        return CONTENTS_PATH."/".$this->cleanContentName($contentName).".md";
    }

    /////////////////////////////////////////////////////////////////////////////
    function getImagePath($image) {
        return IMAGES_PATH."/".$this->cleanImageName($image);
    }

    ///////////////////////////////////////////////////////////////////////////////
    function setDegubInfo($label, $value) {
        $label = count($this->debugInfos)." >>> ".$label;
        $this->debugInfos[$label] = $value;
    }

    ///////////////////////////////////////////////////////////////////////////////
    function getDebugInfos() {  
        return var_export($this->debugInfos,true);
    }

    /////////////////////////////////////////////////////////////////////////////
    function defaultContentProcessing($content) {
        return nl2br($content);
    }

}


?>