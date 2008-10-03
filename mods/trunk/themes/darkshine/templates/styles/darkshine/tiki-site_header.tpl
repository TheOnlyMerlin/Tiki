<div id="header-top">
{if $prefs.feature_sitemycode eq 'y' && ($prefs.sitemycode_publish eq 'y' or $tiki_p_admin eq 'y')}
	{eval var=$prefs.sitemycode}
{/if}
{if $prefs.feature_sitelogo eq 'y'}
	<div id="sitelogo" style="{if $prefs.sitelogo_bgcolor ne ''}background-color: {$prefs.sitelogo_bgcolor}; {/if}text-align: {$prefs.sitelogo_align};"><a href="./" title="{$prefs.sitelogo_title}"><img src="{$prefs.sitelogo_src}" alt="{$prefs.sitelogo_alt}" style="border: none" /></a>
	</div>
{/if}
</div>
<div class="clearfix" id="topcontent">
	{if $filegals_manager ne 'y' and $print_page ne 'y'}
	{if $prefs.feature_sitesearch eq 'y' and $prefs.feature_search eq 'y' and $tiki_p_search eq 'y'}
	<div id="sitesearchbar">
		{if $prefs.feature_search_fulltext eq 'y'}
		<a href="tiki-searchresults.php">{tr}Search{/tr}</a>
		{else}
		<a href="tiki-searchindex.php">{tr}Search{/tr}</a>
		{/if}
	</div>
	{/if}
	{/if}
	{* Dynamic content block holds links *}
	{content id=1}
</div>
	{if $prefs.feature_sitead eq 'y' && ($sitead_publish eq 'y' or $tiki_p_admin eq 'y')}
	<div id="adspace">
		<div id="sitead" align="center">
			{eval var=$sitead}
		</div>
	</div>
	{/if}
	{* Top Bar Custom Code goes here *}
	{if $prefs.feature_siteidentity eq 'y' and $prefs.feature_topbar_custom_code}
		{eval var=$prefs.feature_topbar_custom_code}
	{/if}
</div>