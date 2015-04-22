stupidCMSBoilerplate
====================

Install
-------
Nothing

Variabilize pages
-----------------
- Create `my-page.php` at the site root
- Add `<?php require_once __DIR__."/libs/tools.php" ?>` at the top of every pages
- Contents : 
	- Add `<?php _section("section-name")?>` sections in your php files
	- Edit `libs/config.php` and add section names in `$contents`
- Images : 
	- Add `<img src="<?php _img("image-name.jpg")?>"/>` images in your php files
	- Edit `libs/config.php` and add section names in `$images`

Admin
-----
- Open `http://site.com/admin` in browser
- Bonus : 
	- Surrond a defined constant with %% : `%%A_DEFINED_CONSTANT%%`
	test