<?php

// $Id$

// \brief Wiki plugin to play mp3 files
// @author damian aka damosoft, 30 March 2004

function wikiplugin_xspf_help() {
        return tra("Displays an XSPF based Flash Music Player on the wiki page").":<br />~np~{XSPF(song=url_to_mp3,playlist=url_to_xspf_playlist,player=full|slim|button,autoplay=true|false,title=text)}{XSPF}~/np~";
}

function wikiplugin_xspf($data, $params) {

	extract ($params,EXTR_SKIP);

	if ((!isset($playlist)) && (!isset($song))) return "Specify playlist or song parameter url to use and setup xspf";

	if (!isset($title)) $title='XSPF Player';
	if (!isset($autoplay)) $autoplay='true';
	if (!isset($autoload)) $autoload='true';
	if (!isset($player)) $player='slim';

	if ($player=='full') {
	if (!isset($width)) $width=400;
	if (!isset($height)) $height=168;
	if (!isset($movie)) $movie='lib/xspf_player/xspf_player.swf';
	} elseif ($player=='button') {
	if (!isset($width)) $width=18;
	if (!isset($height)) $height=18;
	if (!isset($movie)) $movie='lib/xspf_player/musicplayer.swf';
	} else {
	if (!isset($width)) $width=400;
	if (!isset($height)) $height=16;
	if (!isset($movie)) $movie='lib/xspf_player/xspf_player_slim.swf';
	}

	if (!isset($playlist)) $playlist='lib/xspf_player/playlist.xspf';

	if (isset($song)) {
	    $xspf = "<object width=\"$width\" height=\"$height\" data=\"$movie?song_url=$song&autoplay=$autoplay&autoload=$autoload&song_title='$title'\" type=\"application/x-shockwave-flash\">";
	$xspf .= "<param value=\"$movie?song_url=$song&autoplay=$autoplay&autoload=$autoload&song_title='$title'\" name=\"movie\"></param></object>"; }
	else {
	$xspf = "<object width=\"$width\" height=\"$height\" data=\"$movie?playlist_url=$playlist&autoplay=$autoplay&autoload=$autoload&player_title='$title'\" type=\"application/x-shockwave-flash\">";
	$xspf .= "<param value=\"$movie?playlist_url=$playlist&autoplay=$autoplay&autoload=$autoload&player_title='$title'\" name=\"movie\"></param></object>";
	}

	return $xspf;
}
