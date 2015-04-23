<?php

/////////////////////////////////////////////////////////////////////////////
require_once __DIR__."/../libs/tools.php";

if(isset($_POST["password"])) {
	login($_POST["password"]);
}

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
			<nav>Authentification needed</nav>
		</header>

		<form method="post">
			<div class="form-group">
				<input type="password" name="password" placeHolder="admin password" class="form-control"/>
			</div>
			<input type="submit" name="login" value="login" class="btn btn-primary"/>
			
		</form>

	</div>

	<?php if(DEBUG_MODE) :?>
		<div class="debug">DEBUG : <?php echo getDebugInfos();?></div>
	<?php endif;?>

</body>
</html>