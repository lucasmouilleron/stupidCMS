stupidCMS
=========

stupidCMS is a flat file stupid CMS.

Install
-------
- Login password is defined in `./__stupid/libs/config.php`
- Desactivate debug mode in production in `./__stupid/libs/config.php`

Stupid Pages
------------
- _Stupid pages_ are located at the site root `./`
- _Stupid pages_ files extensions must be `.html`
- In _stupid pages_, paths are relative to the site root
- To link from a page to another page, use `http://site.com/other-page`
- They use the _stupid micro templating engine_ (SMTE) allowing administrable content declaration

Stupid Micro Templating Engine (SMTE)
-------------------------------------
- Administrable contents : `{{CNT:section-name}}`
- Administrable images : `<img src="{{IMG:image-name.jpg}}"/>`
- Include a definition (PHP constant, e.g. defined in `./__stupid/libs/config.php`) : `{{DEF:CONSTANT_NAME}}`
- Tip : declare images in `_additionalImages.html` if they are only referenced via administrable contents

Stupid backend
--------------
- stupidCMS comes with a stupid backend
- Open `http://site.com/admin` in browser
- If SMTE administrable content are not visible, run `Scan Pages`
- If SMTE new contents are not visible, run `Clear Cache`
- Content editing tips : 
	- Contents are written in markdown format : [Markdown documentation](https://github.com/adam-p/markdown-here/wiki/Markdown-Cheatsheet)
	- Contents are also SMTE compatible
- Image editing tips : todo

Miscs
-----
- Underlying cotents in `./__stupid/_contents`
- Underlying images in `./__stupid/_images`

TODO
----
- page inclusions
- sub folder index