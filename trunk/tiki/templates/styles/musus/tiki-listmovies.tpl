{* $Header: /cvsroot/tikiwiki/tiki/templates/styles/musus/tiki-listmovies.tpl,v 1.4 2004-01-29 03:36:41 musus Exp $ *}
<a class="pagetitle"><a href="tiki-listmovies.php">{tr}List Movies{/tr}</a>
Those movies are very good tutorials of tikiwiki use. They have been generated by siridhar with camtasia studio for public use ! Thanks to him :)
The up-to-date list of movies is on <a href="/TikiMovies">TikiMovies</a>.<br /><br />
<form>
{tr}Choose a movie{/tr}:
<select name="movie">
{foreach key=k item=swf from=$movies}
<option value="{$swf}"{if $swf eq $movie} selected="selected"{/if}>{$swf|replace:".swf":""}</option>
{/foreach}
</select>
<input type="submit" name="action" value="{tr}Display{/tr}">
</form>
{if $movie}
<div class="tiki">
<div class="tiki-title">{$movie}</div>
<div class="tiki-content">
<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0" width="788" height="461" id="myMovieName">
<param name="movie" value="tikimovies/controller.swf?csConfigFile=tikimovies/{$movie|replace:".swf":".xml"}">
<param name="quality" value="high">
<param name="bgcolor" value="#FFFFFF">
<param name="FlashVars" value="csConfigFile=tikimovies/{$movie|replace:".swf":".xml"}">
<embed src="tikimovies/controller.swf?csConfigFile=tikimovies/{$movie|replace:".swf":".xml"}" FlashVars="csConfigFile=tikimovies/{$movie|replace:".swf":".xml"}" quality="high" bgcolor="#FFFFFF" width="788" height="461" type="application/x-shockwave-flash"
pluginspace="http://www.macromedia.com/go/getflashplayer">
</embed>
</object>
</div></div>
{/if}
