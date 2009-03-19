{* $Id$ *}
<div class="clearfix" id="header-top">
{* Custom code ... *}
{if $prefs.feature_sitemycode eq 'y' && ($prefs.sitemycode_publish eq 'y' or $tiki_p_admin eq 'y')}
	<div id="customcode">
	{eval var=$prefs.sitemycode}
	</div>
{else}
	{* site logo *}
	{if $prefs.feature_sitelogo eq 'y'}
		<div id="sitelogo" style="{if $prefs.sitelogo_bgcolor ne ''}background-color: {$prefs.sitelogo_bgcolor}; {/if}text-align: {$prefs.sitelogo_align};{if $prefs.sitelogo_bgstyle} background: {$prefs.sitelogo_bgstyle};{/if}"><a href="./" title="{$prefs.sitelogo_title}"><img src="{$prefs.sitelogo_src}" alt="{$prefs.sitelogo_alt}" style="border: none" /></a>
		</div>
		<div id="sitetitles">
			<div id="sitetitle"><a href="index.php">{$prefs.sitetitle}</a></div>
			<div id="sitesubtitle">{$prefs.sitesubtitle}</div>
		</div>
	{/if}
{/if}
{if $filegals_manager ne 'y' and $print_page ne 'y'}
	{if $prefs.feature_site_login eq 'y'}
	{include file="tiki-site_header_login.tpl"}
	{/if}
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