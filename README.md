stupidCMS
=========

Install
-------
- Login password is defined in `libs/config.php`
- Desactivate debug mode in production in `libs/config.php`

Variabilize pages
-----------------
- Add .html pages in `pages`
- Contents : 
	- Add `{{CNT:section-name}}` sections in your pages
	- Run `http://site.com/admin/scan` to update the CMS
- Images : 
	- Add `<img src="{{IMG:image-name.jpg}}"/>` images in your php files
	- Run `http://site.com/admin/scan` to update the CMS
	- If image is only included in a `content`, declare it in `_additionalImages.html`

Admin
-----
- Open `http://site.com/admin` in browser
- If administrable content not visible, run `http://site.com/admin/scan`
- If new contents are not visible, run `http://site.com/admin/clearCache`
- Content editing : 
	- Surrond a defined constant with %% : `%%A_DEFINED_CONSTANT%%`
- Image editing : 
	- TODO

Miscs
-----
- URL rewriting : `my-page` --> `pages/my-page`
- Underlying cotents in `_contents`
- Underlying images in `_images`

TODO
----
- sub folder in pages