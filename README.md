stupidCMS
=========

stupidCMS is a flat file stupid CMS.

Install
-------
- Login password is defined in `./__stupid/libs/config.php`
- Desactivate debug mode in production in `./__stupid/libs/config.php`

Stupid Pages
------------
- _Stupid Pages_ are located at the site root `./`
- _Stupid Pages_ files extensions should be `.html`
- In _Stupid Pages_, paths are relative to the site root
- To link from a page to another page, use `http://site.com/other-page`
- They use the _stupid micro templating engine_ (SMTE) allowing administrable content declaration

Stupid Micro Templating Engine (SMTE)
-------------------------------------
- Administrable contents : `{{CNT:section-name}}`
- Administrable images : `<img src="{{IMG:image-name.jpg}}"/>`
- Include a definition (PHP constant, e.g. defined in `./__stupid/libs/config.php`) : `{{DEF:CONSTANT_NAME}}`
- Include another page in a page : `{{INC:page-name-without-html-extension}}`
- Tip : declare images in `_additionalImages.html` if they are only referenced via administrable contents

Studid Dynamic Page (SDP)
-------------------------
- _Stupid Pages_ files extensions can be `.php`
- In this case the page is a _Stupid Dynamic Page (SDP)_
- Custom php scripting can be addded
- Sutpid API available :
	- `renderDefinition("def-name")`
	- `renderInclusion("page-name-without-html-extension")`
	- `renderContent("section-name")`
	- `renderImage("image-name")`
- No cache is applied

Stupid backend
--------------
- stupidCMS comes with a stupid backend
- Open `http://site.com/admin` in browser
- If SMTE administrable content are not visible, run `Scan Pages`
- If SMTE new contents are not visible, run `Clear Cache`
- Content editing tips : 
	- Contents are also SMTE compatible, which means you can use the SMTE tags
	- Contents can also be written in markdown format : 
		- Prefix your content with *** to enable Markdown
		- [Markdown documentation](https://github.com/adam-p/markdown-here/wiki/Markdown-Cheatsheet)
	
- Image editing tips : todo

Miscs
-----
- Underlying cotents in `./__stupid/_contents`
- Underlying images in `./__stupid/_images`

TODO
----
- sub folder index