$Id$
-=Theme details=-
* __Name:__ Tikipedia (tikipedia.css)
* __Author and maintainer:__ Gary Cunningham-Lee (chibaguy)
* __License:__ GNU/LGPL
* __Author URL:__ http://zukakakina.com
* __Description__: This is an original theme for TikiWiki CMS/Groupware (http://tikiwiki.org) to replicate the visual appearance of the GPL Monobook theme for Mediawiki. This theme uses the *litecss source-ordered layout method (http://sourceforge.net/projects/litecss), and imports layout/layout.css.

-----
For Tiki 3, the Tikipedia theme uses regular user-created modules for the logo and for the "toolbox" that contains wiki-related icons for print, watch, and so on. So when this theme is installed, these modules need to be made on the Admin Modules page (tiki-admin_modules.php).

1) At the top of the left column will be the logo module . . .

Name: site_logo_and_titles (or whatever you like, doesn't actually matter)
Title: Site logo and titles (doesn't display, so rename as you wish)

Data (this goes in the data text area at the bottom of the Admin Modules page, User Modules tab):

*** start module data ***

{if $prefs.feature_sitelogo eq 'y'}
   <div id="sitelogo"{if $prefs.sitelogo_bgcolor ne ''} style="background-color: {$prefs.sitelogo_bgcolor};" {/if}>
      {if $prefs.sitelogo_src}<a href="./" title="{$prefs.sitelogo_title}"><img src="{$prefs.sitelogo_src}" alt="{$prefs.sitelogo_alt}" style="border: none" /></a>
      {/if}
   </div>
   <div id="sitetitles">
      <div id="sitetitle">
         <a href="index.php">{$prefs.sitetitle}</a>
      </div>
      <div id="sitesubtitle">{$prefs.sitesubtitle}
      </div>
   </div>
{/if}

*** end module data ***

After saving the new module, assign it to the top of the left column, with these settings:

Parameters:
decorations=n&flip=n&title=n&nobox=y&theme=tikipedia.css

Groups:
Anonymous, Registered

(In a coming version of Tiki, the standard logo module will have this code, so at that time the logo module can be used in place of this user-made module.)

2) Next in the left column will be . . .

Name: toolbox (this does display, so "toolbox" is used to be consistent with the Mediawiki original)
Name: tikipedia_toolbox (or whatever makes you like; doesn't really matter)
Title : Toolbox (capitalized to be consistent with other modules, but tikipedia.css transforms to lowercase)

Data: (this goes in the data text area at the bottom of the Admin Modules page, User Modules tab) 

*** start module data ***

{if $page}
		<div>{*  icons *}
	{if $pdf_export eq 'y'}
	<a href="tiki-print.php?{query display="pdf"}" title="{tr}PDF{/tr}">{icon _id='page_white_acrobat' alt="{tr}PDF{/tr}"}</a>
	{/if}
	{if $editable and ($tiki_p_edit eq 'y' or $page|lower eq 'sandbox') and $beingEdited ne 'y' or $canEditStaging eq 'y'}
	<a title="{tr}Edit{/tr}" {ajax_href template="tiki-editpage.tpl" htmlelement="tiki-center"}tiki-editpage.php?page={if $needsStaging eq 'y'}{$stagingPageName|escape:"url"}{else}{$page|escape:"url"}{/if}{if !empty($page_ref_id) and $needsStaging neq 'y'}&amp;page_ref_id={$page_ref_id}{/if}{/ajax_href}>{icon _id='page_edit'}</a>
	{/if}
	{if $prefs.feature_morcego eq 'y' && $prefs.wiki_feature_3d eq 'y'}
	<a title="{tr}3d browser{/tr}" href="javascript:wiki3d_open('{$page|escape}',{$prefs.wiki_3d_width}, {$prefs.wiki_3d_height})">{icon _id='wiki3d' alt="{tr}3d browser{/tr}"}</a>
	{/if}
	{if $cached_page eq 'y'}
	<a title="{tr}Refresh{/tr}" href="tiki-index.php?page={$page|escape:"url"}&amp;refresh=1">{icon _id='arrow_refresh'}</a>
	{/if}
	{if $prefs.feature_wiki_print eq 'y'}
	<a title="{tr}Print{/tr}" href="tiki-print.php?{if !empty($page_ref_id)}page_ref_id={$page_ref_id}&amp;{/if}page={$page|escape:"url"}">{icon _id='printer' alt="{tr}Print{/tr}"}</a>
	{/if}
	{if $prefs.feature_tell_a_friend eq 'y' && $tiki_p_tell_a_friend eq 'y'}
	<a title="{tr}Send a link{/tr}" href="tiki-tell_a_friend.php?url={$smarty.server.REQUEST_URI|escape:'url'}">{icon _id='email_link' alt="{tr}Send a link{/tr}"}</a>
	{/if}
	{if $user and $prefs.feature_notepad eq 'y' and $tiki_p_notepad eq 'y'}
	<a title="{tr}Save to notepad{/tr}" href="tiki-index.php?page={$page|escape:"url"}&amp;savenotepad=1{if !empty($page_ref_id)}&amp;page_ref_id={$page_ref_id}{/if}">{icon _id='disk' alt="{tr}Save to notepad{/tr}"}</a>
	{/if}
	{if $user and $prefs.feature_user_watches eq 'y'}
		{if $user_watching_page eq 'n'}
		<a href="tiki-index.php?page={$page|escape:"url"}&amp;watch_event=wiki_page_changed&amp;watch_object={$page|escape:"url"}&amp;watch_action=add{if $structure eq 'y'}&amp;structure={$home_info.pageName|escape:'url'}{/if}">{icon _id='eye' alt='{tr}Monitor this Page{/tr}'}</a>
		{else}
		<a href="tiki-index.php?page={$page|escape:"url"}&amp;watch_event=wiki_page_changed&amp;watch_object={$page|escape:"url"}&amp;watch_action=remove{if $structure eq 'y'}&amp;structure={$home_info.pageName|escape:'url'}{/if}">{icon _id='no_eye' alt='{tr}Stop Monitoring this Page{/tr}'}</a>
		{/if}
	{if $structure == 'y' and $tiki_p_watch_structure eq 'y'}
		{if $user_watching_structure ne 'y'}
		<a href="tiki-index.php?page={$page|escape:"url"}&amp;watch_event=structure_changed&amp;watch_object={$page_info.page_ref_id}&amp;watch_action=add_desc&amp;structure={$home_info.pageName|escape:'url'}">{icon _id='eye_arrow_down' alt='{tr}Monitor the Sub-Structure{/tr}'}</a>
		{else}
		<a href="tiki-index.php?page={$page|escape:"url"}&amp;watch_event=structure_changed&amp;watch_object={$page_info.page_ref_id}&amp;watch_action=remove_desc&amp;structure={$home_info.pageName|escape:'url'}">{icon _id='no_eye_arrow_down' alt='{tr}Stop Monitoring the Sub-Structure{/tr}'}</a>
		{/if}
		{/if}
	{/if}
		</div><!-- END of icons -->
	{if $prefs.feature_backlinks eq 'y' and $backlinks}
	<form action="tiki-index.php" method="get" style="display: block; float: left">
	<select name="page" onchange="page.form.submit()">
	<option>{tr}Backlinks{/tr}...</option>
	{section name=back loop=$backlinks}
		<option value="{$backlinks[back].fromPage}">{$backlinks[back].fromPage}</option>
	{/section}
	</select>
	</form>
	{/if}
	{if $structure == 'y' and count($showstructs) > 1 or $structure eq 'n' and count($showstructs) ne 0 }
			<form action="tiki-index.php" method="post" style="float: left">
				<select name="page_ref_id" onchange="page_ref_id.form.submit()">
					<option>{tr}Structures{/tr}...</option>
		{section name=struct loop=$showstructs}
					<option value="{$showstructs[struct].req_page_ref_id}" {if $showstructs[struct].pageName eq $structure_path[0].pageName}selected="selected"{/if}>
		{if $showstructs[struct].page_alias} 
			{$showstructs[struct].page_alias}
		{else}
			{$showstructs[struct].pageName}
		{/if}
					</option>
		{/section}
				</select>
			</form>
	{/if}

	{if $prefs.feature_multilingual == 'y'}
		<div>
		{include file="translated-lang.tpl" td='n'}
		</div>
	{/if}
{/if}

*** end module data ***

After saving the new module, assign it to position 2 in the left column, with these settings:

Parameters:
theme=tikipedia.css&section=wiki

Groups:
Anonymous, Registered

----

-=Template Files=-
(All file paths from Tiki root directory.)

* templates/styles/tikipedia/credits.tpl
* templates/styles/tikipedia/index.php
* templates/styles/tikipedia/tiki.tpl
* templates/styles/tikipedia/tiki-bot_bar.tpl
* templates/styles/tikipedia/tiki-likepages.tpl
* templates/styles/tikipedia/tiki-pagehistory.tpl
* templates/styles/tikipedia/tiki-rename_page.tpl
* templates/styles/tikipedia/tiki-rollback.tpl
* templates/styles/tikipedia/tiki-show_page.tpl
* templates/styles/tikipedia/tiki-site_header.tpl
* templates/styles/tikipedia/tiki-top_bar.tpl

-=Stylesheet and background images=-
* styles/tikipedia.css
* styles/tikipedia/bullet.gif
* styles/tikipedia/headbg.jpg
* styles/tikipedia/index.php
* styles/tikipedia/logo.png
* styles/tikipedia/logo-indexed.gif
* styles/tikipedia/user.gif

-=Wikiplugins=-
 (No special wiki plugins are included with the Tikipedia theme in this version. Instead, standard Tiki plugins should be used.)

-=Documentation=-
http://themes.tikiwiki.org/Tikipedia
http://zukakakina.com/Tikipedia

 
-=Version=-
3.0
 
-=Changelog=-
3 updated for Tiki 3, imports layout/layout.css and uses fewer custom files
2 Update for TikiWiki 2.0. Switch to *litecss layout.
1 initial package (May 2, 2006)