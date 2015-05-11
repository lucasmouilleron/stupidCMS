stupidCMS
=========

stupidCMS is a flat file stupid CMS with nice templating and caching engines.

Install
-------
- Login password is defined in `./__stupid/libs/stupidConfig.php`
- Desactivate debug mode in production in `./__stupid/libs/stupidConfig.php`
- Drop all files (including `.htaccess`) at your site root

Stupid Pages (SP)
-----------------
- _Stupid Pages_ are located at the site root `./`
- _Stupid Pages_ files extensions should be `.html`
- In _Stupid Pages_, paths are relative to the site root
- To link from a page to another page, use `http://site.com/other-page`
- They use the _Stupid Micro Templating Engine_ (SMTE) allowing administrable content declaration

Stupid Micro Templating Engine (SMTE)
-------------------------------------
- Administrable contents : `{{CNT:content-name}}`
- Administrable images : `<img src="{{IMG:image-name.jpg}}"/>`
- Include another page in a page : `{{INC:page-name-without-html-extension}}`
- If : `{{IF:php expression}}content{{EIF}}`
- Tip : declare images in `_additionalContents.html` if they are only referenced via administrable contents

Studid Dynamic Page (SDP)
-------------------------
- _Stupid Pages_ files extensions can be `.php`
- In this case the page is a _Stupid Dynamic Page (SDP)_
- Custom php scripting can be addded
- `<?php global $stupid;?>` must be called to make the stupid engine available
- Sutpid API available :
	- `$stupid->__inc("page-name-without-html-extension")`
	- `$stupid->__cnt("content-name")`
	- `$stupid->__img("image-name")`
- No cache is applied (it is applied only for included contents)
- Tip : declare contents or images in `_additionalContents.html` if they are only referenced via _SDP_

Compiled Stupid Pages Caching (CSPC)
------------------------------------
- Compiled `Stupid Pages` are _cached_ for performance optimization
- Three engines are available : 
	- The cache engine is selectable in `__stupid/libs/stupidConfig.php`
	- _None_ cache engine : no cache, all pages and contents are loaded from original files (not recommended)
	- _File_ cache engine : cache files are located in `__stupid/_cache`
	- _Redis_ cache engine : redis port is configurable in `__stupid/libs/stupidDefinitions.php`
- Tip : If new contents are not visible, run `Clear Cache` from the `Stupid Backend`

Stupid Backend (SB)
-------------------
- stupidCMS comes with a stupid backend
- Open `http://site.com/admin` in browser
- Content editing tips : 
	- Contents are also SMTE compatible, which means you can use the SMTE tags
	- Contents can also be written in markdown format : 
		- Prefix your content with *** to enable Markdown
		- [Markdown documentation](https://github.com/adam-p/markdown-here/wiki/Markdown-Cheatsheet)
- Tip : If SMTE administrable content are not visible, run `Scan Pages`
- Tip : If SMTE new contents are not visible, run `Clear Cache`

Dev
---
- Install `composer` : `curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer`
- Install dependencies : `cd __stupid && composer install`
- Underlying cotents in `./__stupid/_contents`
- Underlying images in `./__stupid/_images`

TODO
----
- sub folder index
- todo