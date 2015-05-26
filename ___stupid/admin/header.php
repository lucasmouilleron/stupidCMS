<!DOCTYPE html>
<html>
<head>
	<title><?php echo STUPID_NAME?> | Admininistration</title>
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
					<li><a href="admin-contents" class="<?php if(isCurrentPage("admin-contents")) echo "active"?>" data-placement="bottom" data-toggle="tooltip" title="Manage contents">Contents</a></li>
					<li><a href="admin-files" class="<?php if(isCurrentPage("admin-files")) echo "active"?>" data-placement="bottom" data-toggle="tooltip" title="Manage files">Files</a></li>
					<li><a href="admin-pages" class="<?php if(isCurrentPage("admin-pages")) echo "active"?>" data-placement="bottom" data-toggle="tooltip" title="Manage pages">Pages</a></li>
					<li><a href="scan" class="<?php if(isCurrentPage("scan")) echo "active"?>" data-placement="bottom" data-toggle="tooltip" title="Scan new contents and files">Scan</a></li>
					<li><a href="clean" class="<?php if(isCurrentPage("clean")) echo "active"?>" data-placement="bottom" data-toggle="tooltip" title="Clear old contents and files">Clean</a></li>
					<li><a href="clear" class="<?php if(isCurrentPage("clear")) echo "active"?>" data-placement="bottom" data-toggle="tooltip" title="Clear cache">Clear</a></li>
					<li><a href="<?php echo SITE_URL?>" target="new">View site</a></li>
					<li><a href="help" class="<?php if(isCurrentPage("help")) echo "active"?>">Help</a></li>
				</ul>
			</nav>
		</header>
	</div>