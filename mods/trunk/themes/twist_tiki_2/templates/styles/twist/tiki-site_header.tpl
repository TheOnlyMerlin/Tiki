{* $Id$ *}
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
	{/if}
{/if}
{if $filegals_manager ne 'y' and $print_page ne 'y'}
	{if $prefs.feature_site_login eq 'y'}
	{include file="tiki-site_header_login.tpl"}
	{/if}
{/if}