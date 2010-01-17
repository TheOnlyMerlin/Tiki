-------------------------
JQuery/Tiki readme
-------------------------

- - - - - - - - - - - - -

jonnybradley 17 jan 2010

Update:
	jquery.cluetip.js updated to 1.0.6 from GitHub (http://github.com/kswedberg/jquery-cluetip)
	Fixes problems with multiple tips in Google Chrome and doesn't seem to introduce the issues found with 1.0.4
	Needs more testing and replacing with final 1.0.6 release version before Tiki 5.0 release
	Changed clickThrough settings in tiki-jquery.js to true to "correct" fix in 1.0.4 described as
		"Changed slightly the behavior of clickThrough option: no longer test value of href

- - - - - - - - - - - - -

jonnybradley jan 2010

Note:
	branches/proposals/4.x/lib/jquery external now fixed to r23938

- - - - - - - - - - - - -

jonnybradley December 2009

Update:
	jquery.sheet-0.53

- - - - - - - - - - - - -
jonnybradley October 2009

Updates:
	colorbox-1.3.1
	Paths to the colorbox styles dir (lib/jquery/colorbox/styles/) needs appending to the IE class definitions in colorbox.css

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
