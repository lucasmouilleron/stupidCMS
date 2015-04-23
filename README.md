stupidCMS
=========

Install
-------
- Login password is defined in `libs/config.php`
- Desactivate debug mode in production in `libs/config.php`

Pages
-----
- _Pages_ are located in `./pages`
- They use the _stupid micro templating engine_ (SMTE)
- _Pages_ are html files
- In _pages_, paths are relative to the site root 
- Administrable contents : `{{CNT:section-name}}`
- Administrable images : `<img src="{{IMG:image-name.jpg}}"/>`
- Tip : declare images in `_additionalImages.html` if they are only referenced via administrable contents

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