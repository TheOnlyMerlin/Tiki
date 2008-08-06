{* $Id$ *}
{if $filegals_manager ne 'y' and $print_page ne 'y'}
{*if $prefs.feature_site_login eq 'y'*}
	<div id="siteloginbar">
		<a href="tiki-login.php">Login</a>
	</div>
{*/if*}
{/if}
{* Custom code ... *}
{if $prefs.feature_sitemycode eq 'y' && ($prefs.sitemycode_publish eq 'y' or $tiki_p_admin eq 'y')}
	{if $prefs.feature_sitead eq 'y' && ($prefs.sitead_publish eq 'y' or $tiki_p_admin eq 'y')}{* ... and a banner *}
		<div id="sitead" class="floatright">
		{eval var=$prefs.sitead}
		</div>
		<div id="customcodewith_ad">
		{eval var=$prefs.sitemycode}{* here will be parsed the 400px-wide custom site header code *}
		</div>
	{else}
		<div id="customcode">
		{eval var=$prefs.sitemycode}
		</div>
	{/if}
{else}
	{* No sitelogo but a sitead: ad is centered. *}
		{if $prefs.feature_sitead eq 'y' && ($prefs.sitead_publish eq 'y' or $tiki_p_admin eq 'y')}
		<div align="center">
		{eval var=$prefs.sitead}</div>
		{/if}
{/if}
