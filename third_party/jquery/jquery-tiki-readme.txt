-------------------------
JQuery/Tiki readme
-------------------------

jonnybradley 4 May 2010

Update:
	jQuery.sheet from 1.0.2 to 1.1.0 SVN

jonnybradley 9 April 2010

Update:
	jQuery.sheet from 1.0 to 1.0.2

jonnybradley 30 March 2010

Update:
	jQuery.sheet from 1.0 RC1 to 1.0 RC2

jonnybradley 22 March 2010

Update:
	jquery-ui 1.8rc2 to 1.8
		Google CDN still hasn't updated to 1.8 yet, so TODO in tiki-setup.php line 161
	jQuery.sheet from 1.0 b to 1.0 RC


jonnybradley 5 march 2010

New:
	jqs5 - slideshow lib (experimental - small modification to jqs5.js to alias $jq to $)

Upgrade:
	cluetip from 1.0.6 to 1.0.7
	More for jQuery.sheet

jonnybradley 4 march 2010

Upgrade:
	jQuery.sheet from 0.54 to 1.0 b
	Other tidying up of jQueryUI from previous incomplete commit.

jonnybradley 1 march 2010

Upgrade:
	jquery 1.3.1 to 1.4.2
	jquery-ui 1.7.2 to 1.8rc2
		All in lib/jquery/jquery-ui now (some docs were separate in last version)
		Not included "demos" and "tests" directories.
		Also, Google CDN hasn't updated to 1.8 yet, so TODO in tiki-setup.php line 161

jonnybradley 19 feb 2010

Upgrade:
	jquery.sheet upgrade from 0.53 and 0.54 (SVN)

jonnybradley 20 jan 2010

New:
	Add Infinite Carousel 2 for new slideshow

Kil:
	columnManager (was once used in tiki-objectpermissions)
	malsup-cycle (slideshow, never used)

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
