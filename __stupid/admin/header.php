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
				<ul>
					<li><a href="admin-contents" class="<?php if(isCurrentPage("admin-contents")) echo "active"?>">Contents</a></li>
					<li><a href="admin-images" class="<?php if(isCurrentPage("admin-images")) echo "active"?>">Images</a></li>
					<li><a href="admin-pages" class="<?php if(isCurrentPage("admin-pages")) echo "active"?>">Pages</a></li>
					<li><a href="scan-contents" class="<?php if(isCurrentPage("scan-contents")) echo "active"?>">Scan contents</a></li>
					<li><a href="clear-cache" class="<?php if(isCurrentPage("clear-cache")) echo "active"?>">Clear cache</a></li>
					<li><a href="clean-contents" class="<?php if(isCurrentPage("clean-contents")) echo "active"?>">Clean contents</a></li>
					<li><a href="help" class="<?php if(isCurrentPage("help")) echo "active"?>">Help</a></li>
				</ul>
			</nav>
		</header>
	</div>