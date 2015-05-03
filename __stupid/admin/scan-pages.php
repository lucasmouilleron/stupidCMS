<?php

/////////////////////////////////////////////////////////////////////////////
require_once __DIR__."/../libs/stupidBackend.php";
$stupidBackend = new stupidBackend();
$stupidBackend->lockPage();

/////////////////////////////////////////////////////////////////////////////
$contents = array();
$images = array();
$files = $stupidBackend->listPagesFull();

foreach ($files as $file) {
	$content = file_get_contents($file.PAGES_EXTENSION);
	$file = str_replace(PAGES_PATH, "", $file);
	preg_match_all("/\{\{".CONTENT_TAG."(.*)\}\}/U",$content, $matches);
	$results = $matches[1];
	foreach ($results as $result) {
		if(!array_key_exists($result, $contents)) {
			$contents[$result] = array();	
		}
		array_push($contents[$result], $file);
	}
	preg_match_all("/\{\{".IMAGE_TAG."(.*)\}\}/U",$content, $matches);
	$results = $matches[1];
	foreach ($results as $result) {
		if(!array_key_exists($result, $images)) {
			$images[$result] = array();	
		}
		array_push($images[$result], $file);
	}
}

file_put_contents(CONTENTS_FILE, json_encode($contents));
file_put_contents(IMAGES_FILE, json_encode($images));

?>

<?php require_once __DIR__."/header.php";?>

<div class="container">
	<div class="alert alert-success" role="alert">Found <strong><?php echo count($contents)?></strong> contents</div>
	<div class="alert alert-success" role="alert">Found <strong><?php echo count($images)?></strong> images</div>
</div>

<?php require_once __DIR__."/footer.php";?>