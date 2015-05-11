<!DOCTYPE html>
<html>
<head>
	<title>stupidCMS | Admininistration</title>
	<meta name="description" content="">
	<meta name="robots" content="noindex">
	<meta charset="utf-8">
	<link rel="stylesheet" href="./css/main.css">
</head>
<body>

	<div class="container">
		<header>
			<h1>stupidCMS<sup><?php echo STUPID_VERSION?></sup></h1>
			<nav>
				<a href="admin-contents" class="<?php if(isCurrentPage("admin-contents")) echo "active"?>">Contents</a>
				<span class="sep">|</span>
				<a href="admin-images" class="<?php if(isCurrentPage("admin-images")) echo "active"?>">Images</a> 
				<span class="sep">|</span> 
				<a href="scan-pages" class="<?php if(isCurrentPage("scan-pages")) echo "active"?>">Scan pages</a> 
				<span class="sep">|</span> 
				<a href="clear-cache" class="<?php if(isCurrentPage("clear-cache")) echo "active"?>">Clear cache</a>
				<span class="sep">|</span> 
				<a href="help" class="<?php if(isCurrentPage("help")) echo "active"?>">Help</a>
			</nav>
		</header>
	</div>