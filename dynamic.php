<?php global $stupid;?>
<!DOCTYPE html>
<html>
<head>
	<title><?php $stupid->__cnt("general-company")?></title>
	<meta name="description" content="">
	<meta charset="utf-8">
	<link rel="stylesheet" href="assets/css/main.css">
	<meta name="viewport" content="width=device-width initial-scale=1.0">
</head>
<body>

	<div class="container">

		<?php $stupid->__inc("_menu")?>

		<div class="hot">
		<p>This is a PHP page.</p>
		<p>Everything is ran live and custom scripting can be added.</p>
		<p>Such as <?php echo time()?></p>
		</div>

		<div>
            <h2>Administrable image</h2>
            <img src="<?php $stupid->__img("image1.jpg")?>"/>
        </div>

		<?php $stupid->__cnt("index-about")?>

		<?php $stupid->__inc("_footer")?>

	</div>

</body>
</html>