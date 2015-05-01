<!DOCTYPE html>
<html>
<head>
	<title><?php echo renderDefinition("GENERAL_COMPANY")?> | home</title>
	<meta name="description" content="">
	<meta charset="utf-8">
	<link rel="stylesheet" href="assets/css/main.css">
	<meta name="viewport" content="width=device-width initial-scale=1.0">
</head>
<body>

	<div class="container">

		<?php echo renderInclusion("_menu")?>

		<div class="hot">
		<p>This is a PHP page.</p>
		<p>Everything is ran live and custom scripting can be added.</p>
		<p>Such as <?php echo time()?></p>
		</div>

		<div>
            <h2>Administrable image</h2>
            <img src="<?php echo renderImage("image1.jpg")?>"/>
        </div>

		<?php echo renderContent("index-about")?>

		{{INC:_footer}}

	</div>

</body>
</html>