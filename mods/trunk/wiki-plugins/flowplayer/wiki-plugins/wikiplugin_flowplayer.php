<?php

function wikiplugin_flowplayer_help() {
        return tra("Displays a FlowPlayer based Flash Video Player on the wiki page").":<br />~np~{FLOWPLAYER(videofile=url_to_flv_video,configfile=url_to_flowplayer_js_configfile,player=standard|longplay|skinnable|dark,autoplay=true|false,width=pixel,height=pixel)}{FLOWPLAYER}~/np~";
}

function wikiplugin_flowplayer($data, $params) {

	extract ($params,EXTR_SKIP);

	if ((!isset($videofile)) && (!isset($configfile))) return "Specify videofile or configfile parameter to use and setup flowplayer";

	if (!isset($title)) $title='FlowPlayer Video';
	if (!isset($autoplay)) $autoplay='true';
	if (!isset($autoload)) $autoload='true';
	if (!isset($player)) $player='normal';
	if (!isset($width)) $width=320;
	if (!isset($height)) $height=263;

	if ((isset($player)) && ($player=='longplay')) {
	if (!isset($engine)) $engine='/lib/flowplayer/FlowPlayerLP.swf';
	} elseif ((isset($player)) && ($player=='skinnable')) {
		if (!isset($engine)) $engine='/lib/flowplayer/FlowPlayerLight.swf';
	} elseif ((isset($player)) && ($player=='dark')) {
		if (!isset($engine)) $engine='/lib/flowplayer/FlowPlayerDark.swf';
	}else {
		if (!isset($engine)) $engine='/lib/flowplayer/FlowPlayerClassic.swf';
	}

	if (!isset($configfile)) $configfile='/lib/flowplayer/flowPlayer.js';


	$flowplayer = "<embed WIDTH=\"$width\" HEIGHT=\"$height\" src=\"$engine\" TYPE=\"application/x-shockwave-flash\" ";
	$flowplayer .= "allowScriptAccess=\"sameDomain\" ";
	$flowplayer .= "quality=\"high\" ";
	$flowplayer .= "scale=\"noScale\" ";
	if (isset($videofile)) {
	$flowplayer .= "flashvars=\"config={autoPlay: false, videoFile:'$videofile',initialScale:'scale',useNativeFullScreen:true}\"\>"; 
	}
	else {
	$flowplayer .= "flashvars=\"configFileName=$configfile\">"; 
	}

	return $flowplayer;
}

?>
