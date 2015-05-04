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

    ///////////////////////////////////////////////////////////////////////////////
    function clearSMTECache() {
        $this->cacheEngine->clearCache();
        $pages = $this->listPages();
        foreach ($pages as $page) {
            $this->cacheEngine->setToCache($page,$this->renderPage($page, true));
            $this->setDegubInfo("cacheGenerated",$page);
        }
        return $pages;
    }

    ///////////////////////////////////////////////////////////////////////////////
    function processPage($page) {
        $page = $this->cleanPageName($page);
        if($this->isPageDynamic($page)) {
            include(PAGES_PATH."/".$page.".php");
        }
        else if($this->isPageRenderable($page)){ 
            echo @$this->renderPage($page);
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
            $this->setDegubInfo($page." loadedFromCache","true");
            return $this->cacheEngine->getFromCache($page);
        }
        else {
            $this->setDegubInfo($page." loadedFromCache",false);
            if(SMTE_CACHE_AUTO_GENERATE && $noCache == false) {
                $this->clearSMTECache();
            }
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
        return preg_replace_callback("/\{\{(.*)\}\}/U", function($matches) {
            global $noCache;
            $result = $matches[1];
            if(startsWith($result,INCLUDE_TAG)) {
                $result = $this->renderInclusion(substr($result, strlen(DEFINITION_TAG)), $noCache);
            }
            if(startsWith($result,DEFINITION_TAG)) {
                $result = $this->renderDefinition(substr($result, strlen(DEFINITION_TAG)));
            }
            if(startsWith($result,CONTENT_TAG)) {
                $result = $this->renderContent(substr($result, strlen(CONTENT_TAG)));
            }
            if(startsWith($result,IMAGE_TAG)) {
                $result = $this->renderImage(substr($result, strlen(IMAGE_TAG)));
            }
            return $result;
        }, $content);
    }

    ///////////////////////////////////////////////////////////////////////////////
    function renderDefinition($def) {
        return @constant($def);
    }

    ///////////////////////////////////////////////////////////////////////////////
    function renderInclusion($inclusionName, $noCache=false) {
        return $this->renderPage($inclusionName, $noCache);
    }

    ///////////////////////////////////////////////////////////////////////////////
    function renderContent($sectionName) {
        $content = @file_get_contents($this->getMDFilePath($this->cleanSectionName($sectionName)));
        if(startsWith($content,CONTENT_MARKDOWN_PREFIX)) {
            $content = markdownToHTML(substr($content, strlen(CONTENT_MARKDOWN_PREFIX)));
        }
        return $this->renderSMTETemplate($this->replaceWithDefines($content));
    }

    /////////////////////////////////////////////////////////////////////////////
    function renderImage($image) {
        return IMG_URL."/".$this->cleanImageName($image);
    }

    /////////////////////////////////////////////////////////////////////////////
    function cleanSectionName($sectionName) {
        return preg_replace(array("/\s/", "/\.[\.]+/", "/[^\w_\.\-]/"), array('_', '.', ''), $sectionName);
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
    function replaceWithDefines($str) {
        return preg_replace_callback("/\%\%(.*)\%\%/si", 
            function($matches) {
                return constant($matches[1]);
            }, $str);
    }

    /////////////////////////////////////////////////////////////////////////////
    function getMDFilePath($section) {
        return CONTENTS_PATH."/".$this->cleanSectionName($section).".md";
    }

    /////////////////////////////////////////////////////////////////////////////
    function getImagePath($image) {
        return IMAGES_PATH."/".$this->cleanImageName($image);
    }

    ///////////////////////////////////////////////////////////////////////////////
    function setDegubInfo($label, $value) {
        $label .= uniqid();
        $this->debugInfos[$label] = $value;
    }

    ///////////////////////////////////////////////////////////////////////////////
    function getDebugInfos() {  
        return var_export($this->debugInfos,true);
    }

}


?>