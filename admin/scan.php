<?php

/////////////////////////////////////////////////////////////////////////////
require_once __DIR__."/../libs/tools.php";

/////////////////////////////////////////////////////////////////////////////
$scanDir = __DIR__."/..";
$files = scandir($scanDir);
$contents = [];
$images = [];
foreach ($files as $file) {
	if(endsWith($file, ".php")) {
		$content = file_get_contents($scanDir."/".$file);
		preg_match_all("/".CONTENT_FUNCTION."\(\"(.*)\"\)/",$content, $matches);
		$results = $matches[1];
		foreach ($results as $result) {
			$contents[]=$result;
		}
		preg_match_all("/".IMAGE_FUNCTION."\(\"(.*)\"\)/",$content, $matches);
		$results = $matches[1];
		foreach ($results as $result) {
			$images[]=$result;
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

	<!-- /////////////////////////////////////////////////////////////// -->
	<div class="container">

		<header>
			<h1>Administration</h1>
			<nav></nav>
		</header>

		<p>Found <?php echo count($contents)?> contents</p>
		<p>Found <?php echo count($images)?> images</p>
		<p><a href="./">Go back to admin</a></p>
	</div>

</body>
</html>