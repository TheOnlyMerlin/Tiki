-------------------------
JQuery/Tiki readme
-------------------------

- - - - - - - - - - - - -
jonnybradley October 2009

Updates:
	colorbox-1.3.1
	N.B. commented out IE specific classes in colorbox.css (last 8 lines)
		because they were causing a significant rendering delay in IE 7 & 8 and don't validate anyway.
		Seems to be some sort of conflict with the page structure.
		These lines are now included in js_detect.php if pref.feature_iepngfix for IE6 only (tiki 4.0+).

Added:
	jquery.async v1.0.0

- - - - - - - - - - - - -
jonnybradley September 2009

Updates:
	cluetip-1.0.3 (rolled back from 1.0.4 - still causing problems)
	colorbox-1.2.9
	jquery-ui-1.7.2
	js-autocomplete-1.3.2
	jquery.sheet-0.52
	superfish-1.4.8

- - - - - - - - - - - - -
	

This directory /third_party/jquery (/trunk/lib/jquery/) contains the JQuery library and selected plugins for TikiWiki 3.0

The suggested layout is as follows:

	JQuery itself on the root
	JS files taken from the release zip (currently jquery-1.3.2-release.zip) /dist/ directory
		jquery.js (readable version for debugging)
		jquery.min.js	(minified version for production)

Plugins and other additions are added in their default named directories (with version numbers removed where applicable).
These are added generally complete and without modification - such as jquery.ui/ and jquery-autocomplete/
(note: removed plugin "demo" and "test" dirs now, saves another 10MB and several hundred more files)

Duplicate files, such as other copies of jquery.js etc should be removed to avoid conflicts. Demos could also be removed if large.

The dir /lib/jquery_tiki/ is used for custom files for connect Tiki to JQuery such as tiki-jquery.js.

JQuery runs at the moment in "compatibility mode" to monimise conflicts with MooTools - so you have to use $jq or jQuery to access the object.
