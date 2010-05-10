<?php

function wikiplugin_flowplayer_help() {
        return tra("Displays a FlowPlayer based Flash Video Player on the wiki page").":<br />~np~{FLOWPLAYER(videofile=url_to_flv_video,configfile=url_to_flowplayer_js_configfile,player=standard|longplay|skinnable|dark,autoplay=true|false,width=pixel,height=pixel)}{FLOWPLAYER}~/np~";
}

function wikiplugin_flowplayer($data, $params) {

	extract ($params,EXTR_SKIP);

	if ((!isset($videofile)) && (!isset($configfile))) return "Specify either videofile or configfile parameter to use and setup flowplayer";

	if (!isset($title)) $title='FlowPlayer Video';
	if (!isset($autoplay)) $autoplay='true';
	if (!isset($autoload)) $autoload='true';
	if (!isset($player)) $player='normal';
	if (!isset($width)) $width=320;
	if (!isset($height)) $height=263;

	if ((isset($player)) && ($player=='longplay')) {
	if (!isset($engine)) $engine='lib/flowplayer/FlowPlayerLP.swf';
	} elseif ((isset($player)) && ($player=='skinnable')) {
		if (!isset($engine)) $engine='lib/flowplayer/FlowPlayerLight.swf';
	} elseif ((isset($player)) && ($player=='dark')) {
		if (!isset($engine)) $engine='lib/flowplayer/FlowPlayerDark.swf';
	}else {
		if (!isset($engine)) $engine='lib/flowplayer/FlowPlayerClassic.swf';
	}

	if (!isset($configfile)) $configfile='lib/flowplayer/flowPlayer.js';

	$flowplayer = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" type="application/x-shockwave-flash" data="'.$engine.'" width="'.$width.'" height="'.$height.'">';
	if (isset($videofile)) {
		$flowplayer .= "<param name=\"flashvars\" value=\"config={autoPlay: $autoplay, videoFile:'$videofile',initialScale:'scale',useNativeFullScreen:true}\" />";
	}
	else {
		$flowplayer .= "<param name=\"flashvars\" value=\"configFileName=$configfile\" />"; 
	}
	$flowplayer .= "<embed width=\"$width\" height=\"$height\" src=\"$engine\" type=\"application/x-shockwave-flash\" ";
	$flowplayer .= "allowScriptAccess=\"sameDomain\" ";
	$flowplayer .= "quality=\"high\" ";
	$flowplayer .= "scale=\"noScale\" ";
	if (isset($videofile)) {
		$flowplayer .= "flashvars=\"config={autoPlay: $autoplay, videoFile:'$videofile',initialScale:'scale',useNativeFullScreen:true}\" />"; 
	}
	else {
		$flowplayer .= "flashvars=\"configFileName=$configfile\" />"; 
	}
	$flowplayer .= "</object>";

	return $flowplayer;
}
