
<?php
/*
 * GOOGLE CALENDAR plugin. Embed a Google Calendar in a wiki page.
 * Contributed  by Rick Sapir (ricks99) for TikiWiki. Released as LGPL.
 * 
 * Syntax:
 * 	{GOOGLEGALENDAR(calId=XXX, width=###, height=###, view=week|month|agenda, start=1-7, title=y|n, navigation=y|n, date=y|n, tabs=y|n, calendars=y|n, bg=ffffff, border=#) /}
 * 
 *  Where:
 *	Required:
 *	calId = Your Google calendar address. You can get this from your Calendar Details page.
 *
 *	Optional:
 *	width = Width (in pixels) of the embedded calendar. Default = 800.
 *	height = Height (in pixels) of the embedded calendar. Default = 600.
 *	view = Default view of the calendar: week, month, or agenda. Default = week.
 *	start = Starting day of the week. Default = 1 (Sunday).
 *	title = Show the calendar title? Default = y.
 *	navigation = Show the navigation buttons? Default = y.
 *	date = Show the calendar date range? Default = y.
 *	tabs = Show the tabs to switch views? Default = y.
 *	calendars = Show other calendars (if available, as defined by your Google account)? Default = n.
 *	bg = Background color (in hexadecimal format) of the calendar. Default = ffffff.
 *	border = Width (in pixels) of the border around the calendar. Default = 0 (no border).
 *	links = Show links to the XML, ICAL, and HTML for the calendar? Default = y.
 *
 */	
function wikiplugin_googlecalendar_help() {
	return tra("Embed a Google calendar in a wiki page").":<br />~np~{GOOGLEGALENDAR(calId=XXX, width=###, height=###, view=week|month|agenda, start=1-7, title=y|n, navigation=y|n, date=y|n, tabs=y|n, calendars=y|n, bg=ffffff, border=#) /}~/np~";
}

function wikiplugin_googlecalendar($data, $params) {
	extract ($params,EXTR_SKIP);


// get calendar id (required)
	if (empty($calId)) {
	return tra('Missing parameter CALID -- the Google calendar ID. This parameter is required.');
	}

	
// set defaults	
// these settings will override the calendar defaults you specify from your Google calendar details page.
	if (empty($width)) {
		$width = "800";
	}

	if (empty($height)) {
		$height = "600";
	}

	if ((empty($view)) or (($view != 'month') and ($view != 'agenda'))) {
		$view = "week";
		} 

	if ((empty($start)) or (($start > "7") and ($start < "1"))) {
		$start = "1";  // 1=sunday, 2=monday, 3=tuesday, 4=wednesday, 5=thursday, 6=friday, 7=saturday
		}
		
	if ((empty($title)) or ($title != 'n')) {
		$title = "1"; } else {
		$title = "0";
		}

	if ((empty($navigation)) or ($navigation != 'n')) {
		$navigation = "1"; } else {
		$navigation = "0";
		}

	if ((empty($date)) or ($date != 'n')) {
		$date = "1"; } else {
		$date = "0";
		}

	if ((empty($tabs)) or ($tabs != 'n')) {
		$tabs = "1"; } else {
		$tabs = "0";
		}

	if ((empty($calendars)) or ($calendars != 'y')) {
		$calendars = "0"; } else {
		$calendars = "1";
		}
		
	if (empty($bg)) {
		$bg = "ffffff"; 
	}

	if (empty($border)) {
		$border = "0";
		}
		
	if ((empty($links)) or ($links != 'n')) {
		$links = "y"; 
		}
	
		
	$data = '<iframe src="http://www.google.com/calendar/embed?showTitle='.$title.'&amp;showNav='.$navigation.'&amp;showDate='.$date.'&amp;showTabs='.$tabs.'&amp;showCalendars='.$calendars.'&amp;mode='.$view.'&amp;height='.$height.'&amp;wkst='.$start.'&amp;hl=en&amp;bgcolor=%23'.$bg.'&amp;src='.$calId.'" style=" border:'.$border.'px solid #000000" width="'.$width.'" height="'.$height.'" frameborder="0" scrolling="no"></iframe>';

	if ($links == "y" ) {
		$data .= '<p>Export this calendar to: <a href="http://www.google.com/calendar/feeds/'.$calId.'/public/basic" title="XML">XML</a>, <a href="http://www.google.com/calendar/ical/'.$calId.'/public/basic.ics" title="ICAL">ICAL</a>, or <a href="http://www.google.com/calendar/embed?src='.$calId.'" title="HTML">HTML</a>. </p>';
	}
	
	return $data;
}
?>
