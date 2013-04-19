$Id$
-=Theme details=-
* __Name:__ Twenty Ten (twentyten.css)
* __Author and maintainer:__ Gary Cunningham-Lee (chibaguy)
* __License:__ GNU/LGPL
* __Author URL:__ http://zukakakina.com
* __Description__: This is an original theme for Tiki Wiki CMS Groupware (http://tiki.org) to replicate the visual appearance of the default theme for WordPress, Twenty Ten. This theme uses the *litecss source-ordered layout method (http://sourceforge.net/projects/litecss), and imports styles/layout/layout.css and styles/layout/design.css.

Files:
styles/twentyten/Readme.txt
styles/twentyten.css
styles/twentyten/index.php
styles/twentyten/headers/berries.jpg
styles/twentyten/headers/cherryblossoms.jpg
styles/twentyten/headers/concave.jpg
styles/twentyten/headers/fern.jpg
styles/twentyten/headers/forestfloor.jpg
styles/twentyten/headers/inkwell.jpg
styles/twentyten/headers/path.jpg
styles/twentyten/headers/sunset.jpg
templates/styles/twentyten/credits.tpl
templates/styles/twentyten/index.php


Description:
The Twenty Ten theme uses the default *litecss source-ordered layout method (http://sourceforge.net/projects/litecss) of Tiki Wiki CMS Groupware and imports layout.css and design.css. To retain the original WordPress layout, use only the center column and right column. Also to best emulate the original look of the theme, these are the recommended module assignments (in this order) -> Top: logo (nobox=y) [only the site title and site subtitle should be input, no logo (unless its height is less than that of the site title maybe); twentyten_header_image (nobox=y) [This is a custom module that contains the wide header image. Its data can either be a link to a single image, or something like "{img  randomGalleryId=n}" to rotate images (display them sequentially) from a gallery (where n is the gallery Id, and all images should be the same size) It should be possible to put (layer) other modules on top of this one if desired; Topbar: login_box (nobox=y, mode=popup), menu (id=43 [or whatever is appropriate], type=horiz, menu_id=tiki-top, nobox=y).
 
Docurl:
http://themes.tikiwiki.org/Twentyten
http://zukakakina.com/Twentyten
 
Licence:
This theme created for Tiki Wiki CMS Groupware is a derived work using visual elements of the Twenty Ten theme for WordPress, which was released under the GPL license. Consequently, the visual aspects retain the GPL license, while the  structural aspects based on the default Tiki components are licensed GNU/LGPL.
 
Author:
Gary Cunningham-Lee (chibaguy).
 
Version:
Tiki 11
 
Changelog:
3 update for Tiki 11, minor changes
2 update for Tiki 9, header image display method changed, other fixes
1 initial package (September 24, 2011)