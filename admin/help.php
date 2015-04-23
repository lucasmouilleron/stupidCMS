<?php

/////////////////////////////////////////////////////////////////////////////
require_once __DIR__."/../libs/tools.php";
lockPage();

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

	<?php require_once __DIR__."/header.php";?>

	<div class="container">
		<?php echo markdownToHTML(file_get_contents(README_FILE))?>
	</div>

	<?php require_once __DIR__."/footer.php";?>

</body>
</html>