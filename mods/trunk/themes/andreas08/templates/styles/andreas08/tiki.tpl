{* $Id$ *}{include file="header.tpl"}
{* Index we display a wiki page here *}
{if $prefs.feature_bidi eq 'y'}
	<div dir="rtl">
{/if}
{if $prefs.feature_ajax eq 'y'}
	{include file="tiki-ajax_header.tpl"}
{/if}
{if $prefs.feature_fullscreen != 'y' or $smarty.session.fullscreen != 'y'}
<div id="tiki-main">
	{if $user eq 'admin' and $tiki_upgrade eq 'y'}
		<div style="background: #ee0000; color: white; border: 2px solid #990000; margin: 1px 1px; width: 99$; clear: both; font-weight: bold; text-align: center;">A new version of TikiWiki has been released: {$tiki_release}!  You are currently running {$tiki_version}.</div>
	{/if}
		<table width="100%" cellpadding="0" cellspacing="0" id="main">
			<tr id="cols">
				<td rowspan="3" id="leftmargin">&nbsp;</td>
				<td colspan="3{* change to 5 if the 2 border tds are used *}" id="header">
				<div class="clearfix" id="header-top">
				{if $prefs.feature_siteidentity eq 'y' and $filegals_manager ne 'y'}
				{* Site identity header section *}
					<div class="clearfix" id="siteheader">
						{include file="tiki-site_header.tpl"}
					</div>
				{/if}
				</div>
				{if $prefs.feature_top_bar eq 'y'}
					<div class="clearfix" id="tiki-top">
						{include file="tiki-top_bar.tpl"}
					</div>
				{/if}
				{if $prefs.feature_siteidentity eq 'y' and $prefs.feature_topbar_custom_code}
					<div class="clearfix" id="topbar_custom_code">
						{eval var=$prefs.feature_topbar_custom_code}
					</div>
				{/if}				
				</td>
				<td rowspan="3" id="rightmargin">&nbsp;</td>
				</tr>
				<tr id="midrow">
				{if $prefs.feature_left_column ne 'n' && $left_modules|@count > 0 && $show_columns.left_modules ne 'n'}
				<td id="leftcolumn" valign="top" {if $prefs.feature_left_column eq 'user'}
			style="display:{if isset($cookie.show_leftcolumn) and $cookie.show_leftcolumn ne 'y'}none{else}table-cell;_display:block{/if};"{/if}>
			<h2 class="hidden">Additional/extra/common/generic/related information</h2>
					<div class="colwrapper">
						{section name=homeix loop=$left_modules}
						{$left_modules[homeix].data}
						{/section}
					</div>
				</td>
				{/if}
				<td id="centercolumn" valign="top">
			{/if}
			<hr class="hidden" /> {* for semantic separation of center and side columns *}
			{if $smarty.session.fullscreen neq 'y'}
      		{if $prefs.feature_left_column eq 'user' or $prefs.feature_right_column eq 'user'}
        		<div id="showhide_columns">
      				{if $prefs.feature_left_column eq 'user' && $left_modules|@count > 0 && $show_columns.left_modules ne 'n'}
				<div style="text-align:left;float:left;"><a class="flip" href="javascript:flip('leftcolumn','table-cell');"><img name="leftcolumnicn" class="colflip" src="img/icons/ofo.gif" border="0" alt="+/-" />&nbsp;{tr}Show/Hide Left Menus{/tr}&nbsp;</a>
						</div>
					{/if}
					{if $prefs.feature_right_column eq 'user'&& $right_modules|@count > 0 && $show_columns.right_modules ne 'n'}
        				<div style="text-align:right;float:right;"><a class="flip" href="javascript:flip('rightcolumn','table-cell');">&nbsp;{tr}Show/Hide Right Menus{/tr}&nbsp;<img name="rightcolumnicn" class="colflip" src="img/icons/ofo.gif" border="0" alt="+/-" /></a>
						</div>
					{/if}
        			<br clear="all" />
					</div>
				{/if}
				{/if}
				{if $prefs.feature_tell_a_friend eq 'y' && $tiki_p_tell_a_friend eq 'y' and (!isset($edit_page) or $edit_page ne 'y')}
				<div class="tellafriend"><a href="tiki-tell_a_friend.php?url={$smarty.server.REQUEST_URI|escape:'url'}">{tr}Email this page{/tr}</a>
				</div>
				{/if}
				<div id="tiki-center">
					{$mid_data}
				</div>
				<hr class="hidden" /> {* for semantic separation of center and side columns *}
				{if $prefs.feature_fullscreen != 'y' or $smarty.session.fullscreen != 'y'}
				</td>
	 			{if $prefs.feature_right_column ne 'n' && $right_modules|@count > 0 && $show_columns.right_modules ne 'n'}
				<td id="rightcolumn" valign="top" {if $prefs.feature_right_column eq 'user'} 
			style="display:{if isset($cookie.show_rightcolumn) and $cookie.show_rightcolumn ne 'y'}none{else}table-cell;_display:block{/if};" {/if}>
			<h2 class="hidden">Additional/extra/common/generic/related information</h2>
					<div class="colwrapper">
						{section name=homeix loop=$right_modules}
							{$right_modules[homeix].data}
						{/section}
					</div>
				</td>
			{/if}
			{*<td id="rightborder"><img src=" " alt="." /></td> For content cols - rightmargin graphic border. *}	
		</tr>
		{if $prefs.feature_bot_bar eq 'y'}
		<tr>
		<td colspan="3{* change to 5 if the 2 border tds are used *}" id="footer" colspan="3">
			<div class="wrapper"> 
		  			<div class="content">
    					{include file="tiki-bot_bar.tpl"}
					</div>
			</div>
		</td>
		</tr>
		{/if}
		</table>
	</div>
{/if}
{if $prefs.feature_bidi eq 'y'}
</div>
{/if}
{include file="footer.tpl"}