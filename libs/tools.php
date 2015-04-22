<?php

/////////////////////////////////////////////////////////////////////////////
require_once __DIR__."/config.php";

/////////////////////////////////////////////////////////////////////////////
session_start();
date_default_timezone_set("Europe/Paris");
define("MDS_PATH",__DIR__."/../mds");
define("IMG_PATH",__DIR__."/../images");
define("IMG_URL","./images/");

/////////////////////////////////////////////////////////////////////////////
require_once __DIR__."/vendors/Michelf/Markdown.inc.php"; use \Michelf\Markdown;

///////////////////////////////////////////////////////////////////////////////
function _section($sectionName) {
    echo replaceWithDefines(Markdown::defaultTransform(@file_get_contents(getMDFilePath(clearSectionName($sectionName)))));
}

/////////////////////////////////////////////////////////////////////////////
function _img($image) {
    echo IMG_URL."/".clearImageName($image);
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
    return MDS_PATH."/".clearSectionName($section).".md";
}

/////////////////////////////////////////////////////////////////////////////
function getImagePath($image) {
    return IMG_PATH."/".clearImageName($image);
}

/////////////////////////////////////////////////////////////////////////////
function clearSectionName($sectionName) {
	return preg_replace(array('/\s/', '/\.[\.]+/', '/[^\w_\.\-]/'), array('_', '.', ''), $sectionName);
}

/////////////////////////////////////////////////////////////////////////////
function clearImageName($imageName) {
	return preg_replace(array('/\s/', '/\.[\.]+/', '/[^\w_\.\-]/'), array('_', '.', ''), $imageName);
}

/////////////////////////////////////////////////////////////////////////////
function getURLCacheKilled($url) {
    $query = parse_url($url, PHP_URL_QUERY);
    $ck = "ck=".time();
    if ($query) {
        $url .= "&".$ck;
    } else {
        $url .= "?".$ck;
    }
    return $url;
}

?>