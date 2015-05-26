<?php

/////////////////////////////////////////////////////////////////////////////
require_once __DIR__."/helpers.php";
if(file_exists(__DIR__."/../../config.php")) require_once __DIR__."/../../config.php";
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
        if(DEVELOPMENT_MODE) {
            $this->cacheEngine = new StupidCache();
            $this->setDegubInfo("cacheEngine","none, debug mode");   
        }
        else if(SMTE_CACHE_ENGINE == "redis") {
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

    ///////////////////////////////////////////////////////////////////////////////
    static function setDefaultConstants() {
        self::setDefaultConstantValueIfMissing("DEBUG_MODE",true);
        self::setDefaultConstantValueIfMissing("DEVELOPMENT_MODE",false);
        self::setDefaultConstantValueIfMissing("ADMIN_PASSWORD","password");
        self::setDefaultConstantValueIfMissing("SITE_URL","http://localhost");
        self::setDefaultConstantValueIfMissing("PAGE_404",false);
        self::setDefaultConstantValueIfMissing("NO_SCAN_FOLDERS","none;none2");
        self::setDefaultConstantValueIfMissing("SMTE_CACHE_ENGINE","file");
    }

    ///////////////////////////////////////////////////////////////////////////////
    static function setDefaultConstantValueIfMissing($constantName,$constantDefaultValue) {
        if(!defined($constantName)) {
            define($constantName,$constantDefaultValue);
        }
    }

    ///////////////////////////////////////////////////////////////////////////////
    function clearCache() {
        $this->cacheEngine->clearCache();
        $pages = $this->listPages();
        foreach ($pages as $page) {
            $this->renderPage($page, true);
        }
        $contents = $this->listContents();
        foreach ($contents as $contentName) {
            $this->renderContent($contentName, true);
        }
        return array("pages"=>$pages, "contents"=>$contents);
    }

    ///////////////////////////////////////////////////////////////////////////////
    function processPage($page) {
        $page = $this->cleanPageName($page);
        if($this->isPageDynamic($page)) {
            include(PAGES_PATH."/".$page.DYNAMIC_PAGES_EXTENSION);
        }
        else if($this->isPageRenderable($page)){ 
            eval(@$this->renderPage($page));
        }
    }

    ///////////////////////////////////////////////////////////////////////////////
    function isPageDynamic($page) {
        return file_exists(PAGES_PATH."/".$page.DYNAMIC_PAGES_EXTENSION);
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
    function renderDefinition($def) {
        if(!defined($def)) {
            $this->setDegubInfo("definitionNotFound",$def);
            return false;
        }
        else {
            return BEGIN_ECHO.$this->cleanRenderString(constant($def)).END_ECHO;
        }
    }

    ///////////////////////////////////////////////////////////////////////////////
    function renderInclusion($inclusionName, $noCache=false) {
        $inclusionPath = PAGES_PATH."/".$inclusionName.PAGES_EXTENSION;
        if(!file_exists($inclusionPath)) {
            $this->setDegubInfo("inclusionNotFound",$inclusionPath);
            return false;
        }
        else {
            return $this->renderSMTETemplate(file_get_contents($inclusionPath));
        }
    }

    ///////////////////////////////////////////////////////////////////////////////
    function renderContent($contentName, $noCache=false) {
        if($noCache == false && $this->cacheEngine->isInCache(SMTE_CACHE_CONTENT_PREFIX.$contentName)) {
            $this->setDegubInfo("contentLoadedFromCache",$contentName);
            return $this->cacheEngine->getFromCache(SMTE_CACHE_CONTENT_PREFIX.$contentName);
        }
        else {
            $contentFilePath = $this->getContentFilePath($this->cleanContentName($contentName));
            if(!file_exists($contentFilePath)) {
                $this->setDegubInfo("contentNotFound",$contentName);
                return false;
            }
            else {
                $this->setDegubInfo("contentLoadedFromFile",$contentName);
                $content = @file_get_contents($contentFilePath);
                if(startsWith($content,CONTENT_MARKDOWN_PREFIX)) {
                    $content = markdownToHTML(substr($content, strlen(CONTENT_MARKDOWN_PREFIX)));
                }
                else {
                    $content = $this->defaultContentProcessing($content);
                }
                $contentRender = $this->renderSMTETemplate($content);
                $this->cacheEngine->setToCache(SMTE_CACHE_CONTENT_PREFIX.$contentName,$contentRender);
                $this->setDegubInfo("contentCacheGenerated",$contentName);
                return $contentRender;
            }
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
            $content = @file_get_contents(PAGES_PATH."/".$page.PAGES_EXTENSION);
            if($content == "") {
                header("HTTP/1.0 404 Not Found");
                if(PAGE_404 !== false) {
                    return $this->processPage(PAGE_404);
                }
                else {
                    echo "404";
                    exit();
                }
            }
            $pageRender = $this->renderSMTETemplate($content,$noCache);
            $this->cacheEngine->setToCache($page, $pageRender);
            $this->setDegubInfo("pageCacheGenerated",$page);
            return $pageRender;
        }
    }

    /////////////////////////////////////////////////////////////////////////////
    function renderFile($file) {
        return BEGIN_ECHO.$this->cleanRenderString(FILES_URL."/".$this->cleanFileName($file)).END_ECHO;
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
    function __file($file) {
        eval($this->renderFile($file));
    }

    ///////////////////////////////////////////////////////////////////////////////
    function __def($def) {
        eval($this->renderDefinition($def));
    }

    /////////////////////////////////////////////////////////////////////////////
    function listPages() {
        $noScanFolders = explode(";", NO_SCAN_FOLDERS);
        array_push($noScanFolders,STUPID_PATH);
        array_push($noScanFolders,PAGE_TEMPLATES_PATH);
        array_push($noScanFolders,CONTENTS_PATH);
        array_push($noScanFolders,FILES_PATH);
        $files = getDirContents(PAGES_PATH);
        $pages = array();
        foreach ($files as $file) {
            if(endsWith($file, PAGES_EXTENSION)) {
                $process = true;
                foreach ($noScanFolders as $noScanFolder) {
                    if(startsWith($file,realpath(ROOT_PATH."/".$noScanFolder))) {
                        $process = false;
                        break;
                    }
                }
                if($process) {
                    array_push($pages, $this->cleanPageName(str_replace(PAGES_PATH, "", str_replace(PAGES_EXTENSION, "", $file))));
                }
            }
        }
        return $pages;
    }

    /////////////////////////////////////////////////////////////////////////////
    function listContents($fullPath=false) {
        $contents = @json_decode(file_get_contents(CONTENTS_FILE), true);
        if($contents === null) {
            return array();
        }
        else {
            $contentsArray = array();
            foreach ($contents as $contentName => $content) {
                if($fullPath) {
                    $contentName = CONTENTS_PATH."/".$contentName.CONTENT_EXTENSION;
                }
                array_push($contentsArray, $contentName);
            }
            return $contentsArray;
        }
    }

    /////////////////////////////////////////////////////////////////////////////
    function listFiles($fullPath=false) {
        $files = @json_decode(file_get_contents(FILES_FILE), true);
        if($files === null) {
            return array();
        }
        else {
            $filesArray = array();
            foreach ($files as $fileName => $file) {
                if($fullPath) {
                    $fileName = FILES_PATH."/".$fileName;
                }
                array_push($filesArray, $fileName);
            }
            return $filesArray;
        }
    }

    /////////////////////////////////////////////////////////////////////////////
    function cleanRenderString($string) {
        return addcslashes($string,'"');
    }

    /////////////////////////////////////////////////////////////////////////////
    function decleanRenderString($string) {
        return stripcslashes($string);
    }

    /////////////////////////////////////////////////////////////////////////////
    function cleanContentName($contentName) {
        return ltrim(preg_replace(array("/\s/", "/\.[\.]+/", "/[^\w_\.\-]/"), array('-', '.', ''), $contentName),"/");
    }

    /////////////////////////////////////////////////////////////////////////////
    function cleanFileName($fileName) {
        return preg_replace(array('/\s/', '/\.[\.]+/', '/[^\w_\.\-]/'), array('-', '.', ''), $fileName);
    }

    /////////////////////////////////////////////////////////////////////////////
    function cleanPageName($page) {
        return ltrim($page,"/");
    }

    /////////////////////////////////////////////////////////////////////////////
    function cleanPageNameFile($page) {
        $page = $this->cleanContentName($page);
        if(!endsWith($page, PAGES_EXTENSION)) {
            $page = $page.PAGES_EXTENSION;
        }
        return $page;
    }

    /////////////////////////////////////////////////////////////////////////////
    function getContentFilePath($contentName) {
        return CONTENTS_PATH."/".$this->cleanContentName($contentName).CONTENT_EXTENSION;
    }

    /////////////////////////////////////////////////////////////////////////////
    function getFilePath($file) {
        return FILES_PATH."/".$this->cleanFileName($file);
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


    ///////////////////////////////////////////////////////////////////////////////
    function renderSMTETemplate($content, $noCache=false) {
        $content = $this->cleanRenderString($content,'"');
        $content = preg_replace_callback("/\{\{(.*)\}\}/U", function($matches) {
            global $noCache;
            $result = $matches[1];
            if(startsWith($result,DEFINITION_TAG)) {
                $result = END_ECHO.$this->renderDefinition(substr($result, strlen(DEFINITION_TAG))).BEGIN_ECHO;
            }
            if(startsWith($result,INCLUDE_TAG)) {
                $result = END_ECHO.$this->renderInclusion(substr($result, strlen(INCLUDE_TAG)), $noCache).BEGIN_ECHO;
            }
            if(startsWith($result,CONTENT_TAG)) {
                $result = END_ECHO.$this->renderContent(substr($result, strlen(CONTENT_TAG)), $noCache).BEGIN_ECHO;
            }
            if(startsWith($result,FILE_TAG)) {
                $result = END_ECHO.$this->renderFile(substr($result, strlen(FILE_TAG))).BEGIN_ECHO;
            }
            if(startsWith($result,IF_TAG)) {
                $result = END_ECHO."if (".($this->decleanRenderString(substr($result, strlen(IF_TAG))))."){".BEGIN_ECHO;
            }
            if(startsWith($result,END_IF_TAG)) {
                $result = END_ECHO."};".BEGIN_ECHO;
            }
            return $result;
        }, $content);
return BEGIN_ECHO.$content.END_ECHO;
}


}


?>