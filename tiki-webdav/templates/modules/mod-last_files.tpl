{* $Id$ *}

{tikimodule error=$module_params.error title=$tpl_module_title name="last_files" flip=$module_params.flip decorations=$module_params.decorations nobox=$module_params.nobox notitle=$module_params.notitle}
{section name=ix loop=$modLastFiles}
	{if $smarty.section.ix.first}{if $nonums != 'y'}<ol>{else}<ul>{/if}{/if}
	<li>
		{if $prefs.feature_shadowbox eq 'y' and $modLastFiles[ix].type|substring:0:5 eq 'image'}
			<a class="linkmodule" href="{$modLastFiles[ix].fileId|sefurl:preview}" rel="shadowbox[modLastFiles];type=img">
		{else}
			<a class="linkmodule" href="{$modLastFiles[ix].fileId|sefurl:file}">
		{/if}
			{$modLastFiles[ix].filename|escape}
		</a>
	</li>
	{if $smarty.section.ix.last}{if $nonums != 'y'}</ol>{else}</ul>{/if}{/if}
{sectionelse}
	<em>{tr}No records to display{/tr}</em>
{/section}
{/tikimodule}
