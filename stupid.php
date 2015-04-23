<?php

/////////////////////////////////////////////////////////////////////////////
require_once __DIR__."/libs/tools.php";

/////////////////////////////////////////////////////////////////////////////
$page = $_GET["__page__"];
$content = file_get_contents(PAGES_PATH."/".$page.".html");

$content = preg_replace_callback("/\{\{(.*)\}\}/U", function($matches) {
	$result = $matches[1];
	if(startsWith($result,DEFINITION_TAG)) {
		$result = constant(substr($result, strlen(DEFINITION_TAG)));
	}
	if(startsWith($result,CONTENT_TAG)) {
		$result = _cnt(substr($result, strlen(CONTENT_TAG)));
	}
	if(startsWith($result,IMAGE_TAG)) {
		$result = _img(substr($result, strlen(IMAGE_TAG)));
	}
	return $result;
}, $content);

echo $content;

?>