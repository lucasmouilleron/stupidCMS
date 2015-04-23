<?php

/////////////////////////////////////////////////////////////////////////////
require_once __DIR__."/../libs/tools.php";

/////////////////////////////////////////////////////////////////////////////
$files = scandir(PAGES_PATH);
$contents = array();
$images = array();
foreach ($files as $file) {
	if(endsWith($file, ".html")) {
		$content = file_get_contents(PAGES_PATH."/".$file);
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
}
file_put_contents(CONTENTS_FILE, json_encode($contents));
file_put_contents(IMAGES_FILE, json_encode($images));

?>

<!DOCTYPE html>
<html>
<head>
	<title><?php echo GENERAL_COMPANY?> | Admininistration</title>
	<meta name="description" content="">
	<meta name="robots" content="noindex">
	<meta charset="utf-8">
	<link rel="stylesheet" href="./css/main.css">
</head>
<body>

	<div class="container">

		<header>
			<h1>Administration</h1>
			<nav>Pages scan</nav>
		</header>

		<p>Found <?php echo count($contents)?> contents</p>
		<p>Found <?php echo count($images)?> images</p>
		<p><a href="./">Go back to admin</a></p>
	</div>

</body>
</html>