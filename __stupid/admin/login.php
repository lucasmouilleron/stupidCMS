<?php

/////////////////////////////////////////////////////////////////////////////
require_once __DIR__."/../libs/stupidBackend.php";
$stupidBackend = new stupidBackend();

if(isset($_POST["password"])) {
	$stupidBackend->login($_POST["password"]);
}

?>

<?php require_once __DIR__."/header.php";?>

<div class="container">
	<form method="post">
		<div class="form-group">
			<input type="password" name="password" placeHolder="admin password" class="form-control" autofocus/>
		</div>
		<input type="submit" name="login" value="login" class="btn btn-primary"/>
	</form>
</div>

<?php require_once __DIR__."/footer.php";?>