{* $Id$ *}
{if $drawFullscreen neq 'true'}
	{title help="Draw"}{$name}{/title}
{/if}

<form id="tiki_draw" style="text-align: center;" onsubmit="$('#tiki_draw').saveDraw();return false;">
	<span style="display: none;">
		<textarea id="fileData">{$data}</textarea>
	</span>
	
	<input type="hidden" id="fileId" value="{$fileId}" />
	<input type="hidden" id="galleryId" value="{$galleryId}" />
	<input type="hidden" id="fileName" value="{$name}" />
	<input type="hidden" id="fileWidth" value="{$width}" />
	<input type="hidden" id="fileHeight" value="{$hight}" />
	<input type="hidden" id="archive" value="{$archive}" />
	
	<div id="drawEditor">
		<div id="drawMenu">
			{if $drawFullscreen neq 'true'}
				<button id="drawSave" style="float left;" onclick="$('#tiki_draw').saveDraw();">{tr}Save{/tr}</button>
				<button id="drawRename" onclick="$('#fileName').val($('#tiki_draw').renameDraw());">{tr}Rename{/tr}</button>
				<button id="drawBack">{tr}Back{/tr}</button>
				<button id="drawFullscreen">{tr}Toggle Fullscreen{/tr}</button>
			{else}
				<input type="submit" value="{tr}Save{/tr}" />
			{/if}
		</div>
	</div>
	
	<div id="map">{$map}</div>
</div>    
