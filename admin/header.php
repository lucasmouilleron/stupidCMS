<div class="container">
	<header>
		<h1>Administration</h1>
		<nav>
			<a href="admin-contents" class="<?php if(isCurrentPage("admin-contents.php")) echo "active"?>">Contents</a>
			<span class="sep">|</span>
			<a href="admin-images" class="<?php if(isCurrentPage("admin-images.php")) echo "active"?>">Images</a> 
			<span class="sep">|</span> 
			<a href="scan">Scan pages</a> 
			<span class="sep">|</span> 
			<a href="clearCache">Clear cache</a>
			<span class="sep">|</span> 
			<a href="help" class="<?php if(isCurrentPage("help.php")) echo "active"?>">Help</a>
		</nav>
	</header>
</div>