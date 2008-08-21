<?php
/*
 *
 * IFRAME plugin. Creates an iframe and loads the specified page within the frame.
 *
 * Syntax:
 *
 *  {IFRAME(some parameters)}$data{IFRAME}
 *
 * Syntax:
 *
 * {IFRAME(name=>name, longdescription=>, width=>, height=>, align=>, frameborder=>, marginheight=> marginwidth=> scrolling=>)}source_URL{IFRAME}
 *
 */
function wikiplugin_iframe_help() {
	return tra("iframe").":<br />~np~{IFRAME(name=> width=> height=> align=> frameborder=> marginheight=> marginwidth=> scrolling=>)}".tra("source_URL")."{IFRAME}~/np~";
}

function wikiplugin_iframe($data, $params) {

	extract ($params);

	if (!isset($name)) {
	return ("<b>missing name parameter for plugin</b><br />");
}
	if (!isset($width)) {
	return ("<b>missing width parameter for plugin</b><br />");
}
	if (!isset($height)) {
	return ("<b>missing height parameter for plugin</b><br />");
}
	if (!isset($align)) {
	return ("<b>missing align parameter for plugin</b><br />");
}
	if (!isset($frameborder)) {
	return ("<b>missing frameBorder parameter for plugin</b><br />");
}
	if (!isset($marginheight)) {
	return ("<b>missing marginheight parameter for plugin</b><br />");
}
	if (!isset($marginwidth)) {
	return ("<b>missing marginwidth parameter for plugin</b><br />");
}
	if (!isset($scrolling)) {
	return ("<b>missing scrolling parameter for plugin</b><br />");
}

	$ret = "<iframe name='$name', width='$width' height='$height' align='$align' border='$frameborder' marginheight='$marginheight' marginwidth='$marginwidth' scrolling='$scrolling' src='$data'></iframe><br />";

	return $ret;
}

?>