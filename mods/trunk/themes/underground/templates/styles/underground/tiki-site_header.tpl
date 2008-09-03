<div id="header-top">
</div>
<div class="clearfix" id="tiki-top">
{*if $prefs.feature_top_bar eq 'y'*}
{* menu float right *}
	{if $prefs.feature_sitemenu eq 'y'}
		{if $prefs.feature_phplayers eq 'y'}
			{phplayers id=$prefs.feature_topbar_id_menu type=horiz}
		{else}
			{menu id=$prefs.feature_topbar_id_menu type=horiz css=y}
		{/if}
	{/if}

{*/if*}
</div>
<div id="header-bottom">
	{* search form, second row float right *}
	{if $filegals_manager ne 'y' and $print_page ne 'y'}
	{if $prefs.feature_sitesearch eq 'y' and $prefs.feature_search eq 'y' and $tiki_p_search eq 'y'}
		<div id="sitesearchbar">{if $prefs.feature_search_fulltext eq 'y'}
		{include file="tiki-searchresults.tpl"
								searchNoResults="false"
								searchStyle="menu"
								searchOrientation="horiz"}{else}
		{include file="tiki-searchindex.tpl"
								searchNoResults="false"
								searchStyle="menu"
								searchOrientation="horiz"}{/if}
		</div>
	{/if}
	{/if}
	{* "site slogan," theme-specific feature *}
	{if $filegals_manager ne 'y' and $print_page ne 'y'}
	{if $prefs.feature_sitemycode eq 'y' && ($prefs.sitemycode_publish eq 'y' or $tiki_p_admin eq 'y')}
		{eval var=$prefs.sitemycode}{* here will be parsed the custom site header code *}
	{/if}
	{/if}
	{*<div id="sitesubtitle">{eval var=$prefs.sitesubtitle}</div>
	<div id="sitetitle">{eval var=$prefs.sitetitle}</div>*}
</div>
