<?php

/////////////////////////////////////////////////////////////////////////////
require_once __DIR__."/config.php";
require_once __DIR__."/vendors/Michelf/Markdown.inc.php"; use \Michelf\Markdown;

/////////////////////////////////////////////////////////////////////////////
session_start();
date_default_timezone_set("Europe/Paris");
define("CONTENTS_PATH",__DIR__."/../_contents");
define("IMAGES_PATH",__DIR__."/../_images");
define("PAGES_PATH",__DIR__."/../pages");
define("CONTENT_TAG","CNT:");
define("IMAGE_TAG","IMG:");
define("DEFINITION_TAG","DEF:");
define("CONTENTS_FILE",CONTENTS_PATH."/__index.json");
define("IMAGES_FILE",IMAGES_PATH."/__index.json");
define("IMG_URL","./_images/");

///////////////////////////////////////////////////////////////////////////////
function renderTemplate($content) {
    return preg_replace_callback("/\{\{(.*)\}\}/U", function($matches) {
        $result = $matches[1];
        if(startsWith($result,DEFINITION_TAG)) {
            $result = constant(substr($result, strlen(DEFINITION_TAG)));
        }
        if(startsWith($result,CONTENT_TAG)) {
            $result = renderContent(substr($result, strlen(CONTENT_TAG)));
        }
        if(startsWith($result,IMAGE_TAG)) {
            $result = renderImage(substr($result, strlen(IMAGE_TAG)));
        }
        return $result;
    }, $content);
}

///////////////////////////////////////////////////////////////////////////////
function renderContent($sectionName) {
    return replaceWithDefines(Markdown::defaultTransform(@file_get_contents(getMDFilePath(clearSectionName($sectionName)))));
}

/////////////////////////////////////////////////////////////////////////////
function renderImage($image) {
    return IMG_URL."/".clearImageName($image);
}

/////////////////////////////////////////////////////////////////////////////
function getContentsList() {
    $contents = @json_decode(file_get_contents(CONTENTS_FILE), true);
    if($contents === null) {
        return array();
    }
    else {
        return $contents;
    }
}

/////////////////////////////////////////////////////////////////////////////
function getImagesList() {
    $images = @json_decode(file_get_contents(IMAGES_FILE), true);
    if($images === null) {
        return array();
    }
    else {
        return $images;
    }
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
    return CONTENTS_PATH."/".clearSectionName($section).".md";
}

/////////////////////////////////////////////////////////////////////////////
function getImagePath($image) {
    return IMAGES_PATH."/".clearImageName($image);
}

/////////////////////////////////////////////////////////////////////////////
function clearSectionName($sectionName) {
	return preg_replace(array('/\s/', '/\.[\.]+/', '/[^\w_\.\-]/'), array('_', '.', ''), $sectionName);
}

/////////////////////////////////////////////////////////////////////////////
function clearImageName($imageName) {
	return preg_replace(array('/\s/', '/\.[\.]+/', '/[^\w_\.\-]/'), array('_', '.', ''), $imageName);
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
    if(!isAuthentified()) {
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

/////////////////////////////////////////////////////////////////////////////
function startsWith($haystack, $needle) {
    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
}

/////////////////////////////////////////////////////////////////////////////
function endsWith($haystack, $needle) {
    return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
}

?>