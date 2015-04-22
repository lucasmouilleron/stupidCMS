stupidCMS
=========

Install
-------
Nothing to do

Variabilize pages
-----------------
- Create `my-page.php` at the site root
- Add `<?php require_once __DIR__."/libs/tools.php" ?>` at the top of every pages
- Contents : 
	- Add `<?php _cnt("section-name")?>` sections in your php files
	- Run `http://site.com/admin/scan` to update the CMS
- Images : 
	- Add `<img src="<?php _img("image-name.jpg")?>"/>` images in your php files
	- Run `http://site.com/admin/scan` to update the CMS

Admin
-----
- Open `http://site.com/admin` in browser
- If administrable content not visible, run `http://site.com/admin/scan`
- Bonus : 
	- Surrond a defined constant with %% : `%%A_DEFINED_CONSTANT%%`