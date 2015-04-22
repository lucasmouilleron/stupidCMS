<?php

/////////////////////////////////////////////////////////////////////////////
require_once __DIR__."/config.php";

/////////////////////////////////////////////////////////////////////////////
session_start();
date_default_timezone_set("Europe/Paris");
define("MDS_PATH",__DIR__."/../mds");
define("IMG_PATH",__DIR__."/../images");
define("IMG_URL","./images/");
define("CONTENTS_FILE",__DIR__."/contents.json");
define("IMAGES_FILE",__DIR__."/images.json");
define("CONTENT_FUNCTION","_cnt");
define("IMAGE_FUNCTION","_img");

/////////////////////////////////////////////////////////////////////////////
require_once __DIR__."/vendors/Michelf/Markdown.inc.php"; use \Michelf\Markdown;

///////////////////////////////////////////////////////////////////////////////
function _cnt($sectionName) {
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

/////////////////////////////////////////////////////////////////////////////
function startsWith($haystack, $needle) {
    // search backwards starting from haystack length characters from the end
    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
}

/////////////////////////////////////////////////////////////////////////////
function endsWith($haystack, $needle) {
    // search forward starting from end minus needle length characters
    return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
}

/////////////////////////////////////////////////////////////////////////////
function getContentsList() {
    return json_decode(file_get_contents(CONTENTS_FILE), true);
}

/////////////////////////////////////////////////////////////////////////////
function getImagesList() {
    return json_decode(file_get_contents(IMAGES_FILE), true);
}

?>