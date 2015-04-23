stupidCMS
=========

Install
-------
Nothing to do

Variabilize pages
-----------------
- Add php pages at the site root
- Add `<?php require_once __DIR__."/libs/tools.php" ?>` at the top of every pages
- Contents : 
	- Add `<?php _cnt("section-name")?>` sections in your php files
	- Run `http://site.com/admin/scan` to update the CMS
- Images : 
	- Add `<img src="<?php _img("image-name.jpg")?>"/>` images in your php files
	- Run `http://site.com/admin/scan` to update the CMS
	- If image is only included in a `content`, declare it in `_additionalImages.php`


Admin
-----
- Open `http://site.com/admin` in browser
- If administrable content not visible, run `http://site.com/admin/scan`
- Bonus : 
	- Surrond a defined constant with %% : `%%A_DEFINED_CONSTANT%%`

Miscs
-----
- URL rewriting : `my-page` --> `my-page.php`
- Underlying cotents in `_contents`
- Underlying images in `_images`

TODO
----
- Show were contents and images are found