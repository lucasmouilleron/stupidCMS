stupidCMS
=========

stupidCMS is a flat file stupid CMS.

Install
-------
- Login password is defined in `./libs/config.php`
- Desactivate debug mode in production in `./libs/config.php`

Stupid Pages
------------
- _Stupid pages_ are located in `./pages`
- _Stupid pages_ files extensions must be `.html`
- In _stupid pages_, paths are relative to the site root. For example, in a _page_, `<link rel="stylesheet" href="assets/css/main.css">` references indeed `./assets/css/main.css`
- To link from a page to another page, use http://site.com/other-page (and not http://site.com/pages/other-page nor http://site.com/pages/other-page)
- They use the _stupid micro templating engine_ (SMTE) allowing administrable content declaration

SMTE
----
- Administrable contents : `{{CNT:section-name}}`
- Administrable images : `<img src="{{IMG:image-name.jpg}}"/>`
- Tip : declare images in `_additionalImages.html` if they are only referenced via administrable contents

Stupid backend
--------------
- stupidCMS comes with a stupid backend
- Open `http://site.com/admin` in browser
- If SMTE administrable content are not visible, run `Scan Pages`
- If SMTE new contents are not visible, run `Clear Cache`
- Content editing tips : 
	- Surrond a defined constant with %% : `%%A_DEFINED_CONSTANT%%`
- Image editing tips : todo

Miscs
-----
- Underlying cotents in `_contents`
- Underlying images in `_images`

TODO
----
- sub folder index