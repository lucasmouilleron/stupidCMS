stupidCMS
=========

Install
-------
- Login password is defined in `./libs/config.php`
- Desactivate debug mode in production in `./libs/config.php`

Pages
-----
- _Pages_ are located in `./pages`
- _Pages_ are html files
- In _pages_, paths are relative to the site root. For example, `<link rel="stylesheet" href="assets/css/main.css">` in a _page_ references indeed `./assets/css/main.css`
- To link from a page to another page, use http://site.com/other-page (and not http://site.com/pages/other-page nor http://site.com/pages/other-page)
- They use the _stupid micro templating engine_ (SMTE) allowing administrable content declaration

SMTE
----
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