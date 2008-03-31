<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"></meta>
<link rel="stylesheet" href="layersmenu-demo.css" type="text/css"></link>
<link rel="stylesheet" href="layersmenu-old.css" type="text/css"></link>
<link rel="shortcut icon" href="LOGOS/shortcut_icon_phplm.png"></link>
<title>The PHP Layers Menu System</title>
</head>
<body>

<div class="normalbox">
<div class="normal" align="center">
<b><?php print basename(__FILE__); ?> - a file-based example with a (Vertical) Plain Menu</b>
</div>
</div>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="20%" valign="top">
<div style="height: 3px"></div>
<?php
require_once 'lib/PHPLIB.php';
require_once 'lib/layersmenu-common.inc.php';
require_once 'lib/plainmenu.inc.php';
$plainmid = new PlainMenu();
//$plainmid->setPlainMenuTpl('layersmenu-plain_menu.ihtml');
$plainmid->setMenuStructureFile('layersmenu-horizontal-2.txt');
$plainmid->setIconsize(16, 16);
$plainmid->parseStructureForMenu('plainmenu');
print $plainmid->newPlainMenu('plainmenu');
?>
<br />
<center>
<a href="http://phplayersmenu.sourceforge.net/"><img border="0"
src="LOGOS/powered_by_phplm.png" alt="Powered by PHP Layers Menu" height="31" width="88" /></a>
</center>
<br />
<center>
<a href="http://validator.w3.org/check/referer"><img border="0"
src="images/valid-xhtml10.png" alt="Valid XHTML 1.0!" height="31" width="88" /></a>
</center>
<br />
<center>
<a href="http://jigsaw.w3.org/css-validator/"><img border="0"
src="images/vcss.png" alt="Valid CSS!" height="31" width="88" /></a>
</center>
</td>
<td valign="top">
<div class="normalbox">
<div class="normal">
<?php require_once 'README.ihtml'; ?>
</div>
</div>
</td>
</tr>
</table>

</body>
</html>
