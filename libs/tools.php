<?php

/////////////////////////////////////////////////////////////////////////////
require_once __DIR__."/config.php";

/////////////////////////////////////////////////////////////////////////////
session_start();
date_default_timezone_set("Europe/Paris");
define("CACHE_PATH",__DIR__."/cache/");
define("MDS_PATH",__DIR__."/../assets/mds");

/////////////////////////////////////////////////////////////////////////////
require "SimpleCache.php";
require_once __DIR__."/vendors/Google/autoload.php";
require_once __DIR__."/vendors/Michelf/Markdown.inc.php"; use \Michelf\Markdown;

///////////////////////////////////////////////////////////////////////////////
function getSection($sectionName) {
    return replaceWithDefines(Markdown::defaultTransform(file_get_contents(getMDFilePath($sectionName))));
}

/////////////////////////////////////////////////////////////////////////////
function replaceWithDefines($str) {
    return preg_replace_callback("/\%\%(.*)\%\%/si", function($matches) {return constant($matches[1]);}, $str);
}

/////////////////////////////////////////////////////////////////////////////
function createFileIfNotExists($filePath) {
    if (!file_exists($filePath)) {
        file_put_contents($filePath, '');
    }
}

/////////////////////////////////////////////////////////////////////////////
function getMDFilePath($section) {
    return MDS_PATH."/".$section.".md";
}

?>