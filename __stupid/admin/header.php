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
			<nav>
				<a href="admin-contents" class="<?php if(isCurrentPage("admin-contents.php")) echo "active"?>">Contents</a>
				<span class="sep">|</span>
				<a href="admin-images" class="<?php if(isCurrentPage("admin-images.php")) echo "active"?>">Images</a> 
				<span class="sep">|</span> 
				<a href="scan-pages" class="<?php if(isCurrentPage("scan-pages.php")) echo "active"?>">Scan pages</a> 
				<span class="sep">|</span> 
				<a href="clear-cache" class="<?php if(isCurrentPage("clear-cache.php")) echo "active"?>">Clear cache</a>
				<span class="sep">|</span> 
				<a href="help" class="<?php if(isCurrentPage("help.php")) echo "active"?>">Help</a>
			</nav>
		</header>
	</div>