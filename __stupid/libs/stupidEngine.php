<?php

/////////////////////////////////////////////////////////////////////////////
require_once __DIR__."/helpers.php";
require_once __DIR__."/stupidConfig.php";
require_once __DIR__."/stupidDefinitions.php";

///////////////////////////////////////////////////////////////////////////////

class Stupid
{
    ///////////////////////////////////////////////////////////////////////////////
    public $debugInfos = array();

    /////////////////////////////////////////////////////////////////////////////
    function listPages() {
        $files = getDirContents(PAGES_PATH);
        $pages = array();
        foreach ($files as $file) {
            if(endsWith($file, PAGES_EXTENSION) && !startsWith($file,SMTE_CACHE_PATH)) {
                array_push($pages, str_replace(PAGES_PATH, "", str_replace(PAGES_EXTENSION, "", $file)));
            }
        }
        return $pages;
    }

    ///////////////////////////////////////////////////////////////////////////////
    function clearSMTECache() {
        @deleteDirectory(SMTE_CACHE_PATH);
        @mkdir(SMTE_CACHE_PATH);

        $pages = $this->listPages();
        foreach ($pages as $page) {
            @mkdir(dirname(SMTE_CACHE_PATH."/".$page),0777, true);
            file_put_contents(SMTE_CACHE_PATH."/".$page.PAGES_EXTENSION, $this->renderPage($page, true));
            $this->setDegubInfo("cacheGenerated",$page);
        }
        return $pages;
    }

    ///////////////////////////////////////////////////////////////////////////////
    function processPage($page) {
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
        $page = $page.PAGES_EXTENSION;
        if($noCache == false && file_exists(SMTE_CACHE_PATH."/".$page)) {
            $this->setDegubInfo($page." loadedFromCache","true");
            return file_get_contents(SMTE_CACHE_PATH."/".$page);
        }
        else {
            $this->setDegubInfo($page." loadedFromCache",false);
            if(SMTE_CACHE_AUTO_GENERATE && $noCache == false) {
                $this->clearSMTECache();
            }
            ob_start(); 
            $content = @file_get_contents(PAGES_PATH."/".$page);
            if($content == "") {
                echo "404 !";
                exit();
            }
            return $this->renderSMTETemplate($content);
        }
    }

    ///////////////////////////////////////////////////////////////////////////////
    function renderSMTETemplate($content) {
        return preg_replace_callback("/\{\{(.*)\}\}/U", function($matches) {
            $result = $matches[1];
            if(startsWith($result,INCLUDE_TAG)) {
                $result = $this->renderInclusion(substr($result, strlen(DEFINITION_TAG)));
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
    function renderInclusion($inclusionName) {
        return $this->renderPage($inclusionName);
    }

    ///////////////////////////////////////////////////////////////////////////////
    function renderContent($sectionName) {
        $content = @file_get_contents($this->getMDFilePath($this->clearSectionName($sectionName)));
        if(startsWith($content,CONTENT_MARKDOWN_PREFIX)) {
            $content = markdownToHTML(substr($content, strlen(CONTENT_MARKDOWN_PREFIX)));
        }
        return $this->renderSMTETemplate($this->replaceWithDefines($content));
    }

    /////////////////////////////////////////////////////////////////////////////
    function renderImage($image) {
        return IMG_URL."/".$this->clearImageName($image);
    }

    /////////////////////////////////////////////////////////////////////////////
    function clearSectionName($sectionName) {
        return preg_replace(array("/\s/", "/\.[\.]+/", "/[^\w_\.\-]/"), array('_', '.', ''), $sectionName);
    }

    /////////////////////////////////////////////////////////////////////////////
    function clearImageName($imageName) {
        return preg_replace(array('/\s/', '/\.[\.]+/', '/[^\w_\.\-]/'), array('_', '.', ''), $imageName);
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
        return CONTENTS_PATH."/".$this->clearSectionName($section).".md";
    }

    /////////////////////////////////////////////////////////////////////////////
    function getImagePath($image) {
        return IMAGES_PATH."/".$this->clearImageName($image);
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