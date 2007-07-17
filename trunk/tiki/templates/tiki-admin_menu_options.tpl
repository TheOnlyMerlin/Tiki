 {* $Header: /cvsroot/tikiwiki/tiki/templates/tiki-admin_menu_options.tpl,v 1.47 2007-07-17 16:21:46 jyhem Exp $ *}
<h1><a class="pagetitle" href="tiki-admin_menu_options.php?menuId={$menuId}">{tr}Admin Menu{/tr}: {$menu_info.name}</a><br /><br />
<span class="button2"><a href="tiki-admin_menus.php" class="linkbut">{tr}List menus{/tr}</a></span>
<span class="button2"><a href="tiki-admin_menus.php?menuId={$menuId}" class="linkbut">{tr}Edit this menu{/tr}</a></span></h1>

<table><tr>
<td valign="top">
<table class="normal"><tr><td valign="top" class="odd" colspan="2">
<h2>{tr}Edit menu options{/tr}</h2>
<div style="text-align: right;">
<a href="#" onclick="toggle('weburls');toggle('urltop');hide('show');show('hide');" id="show" style="display:block;">{tr}Show Quick Urls{/tr}</a>
<a href="#" onclick="toggle('weburls');toggle('urltop');hide('hide');show('show');" id="hide" style="display:none;">{tr}Hide Quick Urls{/tr}</a>
</div>
</td>
<td valign="top" class="even" id="urltop" style="display:none;">
<h2>{tr}Some useful URLs{/tr}</h2>
<hr />
</td>
</tr>
<tr><td valign="top" class="odd" colspan="2">
<form action="tiki-admin_menu_options.php" method="post">
<input type="hidden" name="optionId" value="{$optionId|escape}" />
<input type="hidden" name="menuId" value="{$menuId|escape}" />
<input type="hidden" name="offset" value="{$offset|escape}" />
 {if !empty($nbRecords)}<input type="hidden" name="nbRecords" value="{$nbRecords|escape}" />{/if}
<table>
<tr class="formcolor"><td>{tr}Name{/tr}:</td><td colspan="3"><input id="menu_name" type="text" name="name" value="{$name|escape}" size="34" /></td></tr>
<tr class="formcolor"><td>{tr}URL{/tr}:</td><td colspan="3"><input id="menu_url" type="text" name="url" value="{$url|escape}" size="34" /></td></tr>
<tr class="formcolor"><td>{tr}Sections{/tr}:</td><td colspan="3"><input id="menu_section" type="text" name="section" value="{$section|escape}" size="34" /></td></tr>
<tr class="formcolor"><td>{tr}Permissions{/tr}:</td><td colspan="3"><input id="menu_perm" type="text" name="perm" value="{$perm|escape}" size="34" /></td></tr>
<tr class="formcolor"><td>{tr}Group{/tr}:</td><td colspan="3">
<select id="menu_groupname" name="groupname[]" size="4" multiple>
<option value="">&nbsp;</option>
{foreach key=k item=i from=$option_groups}
<option value="{$k}" {$i}>{$k}</option>
{/foreach}
</select>
</td></tr>
<tr class="formcolor"><td>{tr}Type{/tr}:</td><td>
<select name="type">
<option value="o" {if $type eq 'o'}selected="selected"{/if}>{tr}option{/tr}</option>
<option value="s" {if $type eq 's'}selected="selected"{/if}>{tr}section level 0{/tr}</option>
<option value='1' {if $type eq '1'}selected="selected"{/if}>{tr}section level 1{/tr}</option>
<option value='2' {if $type eq '2'}selected="selected"{/if}>{tr}section level 2{/tr}</option>
<option value='3' {if $type eq '3'}selected="selected"{/if}>{tr}section level 3{/tr}</option>
<option value="r" {if $type eq 'r'}selected="selected"{/if}>{tr}sorted section level 0{/tr}</option>
<option value="-" {if $type eq '-'}selected="selected"{/if}>{tr}separator{/tr}</option>
</select>
</td>
<td>{tr}Position{/tr}:</td><td><input type="text" name="position" value="{$position|escape}" size="6" /></td></tr>
<tr class="formcolor"><td>&nbsp;</td><td colspan="3"><input type="submit" name="save" value="{tr}Save{/tr}" /></td></tr>
</table>
</form>
</td>

<td valign="top" class="even" id="weburls" style="display:none;">
<table>
<tr><td>{tr}Home{/tr}: </td><td><select name="wikilinks" onchange="setMenuCon(options[selectedIndex].value);return true;">
<option value=",,,">{tr}Choose{/tr} ...</option>
<option value="{$tikiIndex},{tr}Home Page{/tr}">{tr}Home Page{/tr}</option>
<option value="tiki-view_blog.php?blogId={$home_blog},{tr}Home Blog{/tr},feature_blogs,tiki_p_view_blogs">{tr}Home Blog{/tr}</option>
<option value="tiki-browse_gallery.php?galleryId={$home_gallery},{tr}Home Image Gal{/tr},feature_galleries,tiki_p_view_image_gallery">{tr}Home Image Gallery{/tr}</option>
<option value="tiki-list_file_gallery?galleryId={$home_file_gallery},{tr}Home File Gal{/tr},feature_file_galleries,tiki_p_view_file_gallery">{tr}Home File Gallery{/tr}</option>]
</select></td></tr>
<tr><td>{tr}General{/tr}: </td><td><select name="wikilinks" onchange="setMenuCon(options[selectedIndex].value);return true;">
<option value=",,,">{tr}Choose{/tr} ...</option>
<option value="tiki-chat.php,{tr}Chat{/tr},feature_chat,tiki_p_chat">{tr}Chat{/tr}</option>
<option value="tiki-stats.php,{tr}Stats{/tr},feature_stats,tiki_p_view_stats">{tr}Stats{/tr}</option>
<option value="tiki-list_games.php,{tr}Games{/tr},feature_games,tiki_p_play_games">{tr}Games{/tr}</option>
<option value="tiki-browse_categories.php,{tr}Categories{/tr},feature_categories,tiki_p_view_categories">{tr}Categories{/tr}</option>
<option value="tiki-user_preferences.php,{tr}User preferences{/tr}">{tr}User prefs{/tr}</option>
</select></td></tr>
<tr><td>{tr}Wiki{/tr}: </td><td><select name="wikilinks" onchange="setMenuCon(options[selectedIndex].value);return true;">
<option value=",,,">{tr}Choose{/tr} ...</option>
<option value="tiki-index.php,{tr}Wiki Home{/tr},feature_wiki,tiki_p_view">{tr}Wiki Home{/tr}</option>
<option value="tiki-lastchanges.php,{tr}Last changes{/tr},feature_lastChanges,tiki_p_view">{tr}Last changes{/tr}</option>
<option value="tiki-wiki_rankings.php,{tr}Rankings{/tr},feature_wiki_rankings,tiki_p_view">{tr}Rankings{/tr}</option>
<option value="tiki-listpages.php,{tr}List pages{/tr},feature_listPages,tiki_p_view">{tr}List pages{/tr}</option>
<option value="tiki-index.php?page=SandBox,{tr}Sandbox{/tr},feature_sandbox,tiki_p_view">{tr}Sandbox{/tr}</option>
</select></td></tr>
<tr><td>{tr}Images{/tr}: </td><td><select name="wikilinks" onchange="setMenuCon(options[selectedIndex].value);return true;">
<option value=",,,">{tr}Choose{/tr} ...</option>
<option value="tiki-galleries.php,{tr}List galleries{/tr},feature_galleries,tiki_p_view_image_gallery">{tr}List image galleries{/tr}</option>
<option value="tiki-upload_image.php,{tr}Upload image{/tr},feature_galleries,tiki_p_upload_images">{tr}Upload{/tr}</option>
<option value="tiki-galleries_rankings.php,{tr}Gallery Rankings{/tr},feature_gal_rankings,tiki_p_view_image_gallery">{tr}Rankings{/tr}</option>
<option value="tiki-browse_gallery.php?galleryId=,{tr}Browse a gallery{/tr},feature_galleries,tiki_p_view_image_gallery">{tr}Browse a gallery{/tr}</option>
</select></td></tr>
<tr><td>{tr}Articles{/tr}: </td><td><select name="wikilinks" onchange="setMenuCon(options[selectedIndex].value);return true;">
<option value=",,,">{tr}Choose{/tr} ...</option>
<option value="tiki-view_articles.php,{tr}Articles{/tr},feature_articles,tiki_p_read_article">{tr}Articles home{/tr}</option>
<option value="tiki-list_articles.php,{tr}All articles{/tr},feature_articles,tiki_p_read_article">{tr}List articles{/tr}</option>
<option value="tiki-cms_rankings.php,{tr}Rankings{/tr},feature_cms_rankings,tiki_p_read_article">{tr}Rankings{/tr}</option>
<option value="tiki-edit_submission.php,{tr}Submit{/tr},feature_submissions,tiki_p_submit_article">{tr}Submit{/tr}</option>
<option value="tiki-list_submissions.php,{tr}Submissions{/tr},feature_submissions,tiki_p_approve_submission">{tr}Submissions{/tr}</option>
</select></td></tr>
<tr><td>{tr}Blogs{/tr}: </td><td><select name="wikilinks" onchange="setMenuCon(options[selectedIndex].value);return true;">
<option value=",,,">{tr}Choose{/tr} ...</option>
<option value="tiki-list_blogs.php,{tr}List Blogs{/tr},feature_blogs,tiki_p_read_blog">{tr}List Blogs{/tr}</option>
<option value="tiki-blog_rankings.php,{tr}Rankings{/tr},feature_blog_rankings,tiki_p_read_blog">{tr}Rankings{/tr}</option>
<option value="tiki-edit_blog.php,{tr}Create blog{/tr},feature_blogs,tiki_p_create_blogs">{tr}Create blog{/tr}</option>
<option value="tiki-blog_post.php,{tr}Post{/tr},feature_blogs,tiki_p_blog_post">{tr}Post{/tr}</option>
</select></td></tr>
<tr><td>{tr}Files{/tr}: </td><td><select name="wikilinks" onchange="setMenuCon(options[selectedIndex].value);return true;">
<option value=",,,">{tr}Choose{/tr} ...</option>
<option value="tiki-file_galleries.php,{tr}File galleries{/tr},feature_file_galleries,tiki_p_view_file_gallery">{tr}File galleries{/tr}</option>
<option value="tiki-upload_file.php,{tr}Upload file{/tr},feature_file_galleries,tiki_p_upload_files">{tr}Upload file{/tr}</option>
<option value="tiki-file_galleries_rankings.php,{tr}Rankings{/tr},feature_file_galleries_rankings,tiki_p_view_file_gallery">{tr}Rankings{/tr}</option>
</select></td></tr>
<tr><td>{tr}Forums{/tr}: </td><td><select name="wikilinks" onchange="setMenuCon(options[selectedIndex].value);return true;">
<option value=",,,">{tr}Choose{/tr} ...</option>
<option value="tiki-forums.php,{tr}Forums{/tr},feature_forums,tiki_p_forum_read">{tr}Forums{/tr}</option>
<option value="tiki-view_forum.php?forumId=,{tr}View a forum{/tr},feature_forums,tiki_p_forum_read">{tr}View a forum{/tr}</option>
<option value="tiki-view_forum_thread.php?forumId=&amp;comments_parentId=,{tr}View a thread{/tr},feature_forums,tiki_p_forum_read">{tr}View a thread{/tr}</option>8
</select></td></tr>
<tr><td>{tr}FAQs{/tr}: </td><td><select name="wikilinks" onchange="setMenuCon(options[selectedIndex].value);return true;">
<option value=",,,">{tr}Choose{/tr} ...</option>
<option value="tiki-list_faqs.php,{tr}FAQs{/tr},feature_faqs,tiki_p_view_faqs">{tr}FAQs{/tr}</option>
<option value="tiki-view_faq.php?faqId=,{tr}View a FAQ{/tr},feature_faqs,tiki_p_view_faqs">{tr}View a FAQ{/tr}</option>
</select></td></tr>
<tr><td>{tr}Quizzes{/tr}: </td><td><select name="wikilinks" onchange="setMenuCon(options[selectedIndex].value);return true;">
<option value=",,,">{tr}Choose{/tr} ...</option>
<option value="tiki-list_quizzes.php,{tr}Quizzes{/tr},feature_quizzes">{tr}Quizzes{/tr}</option>
<option value="tiki-take_quiz.php?quizId=,{tr}Take a quiz{/tr},feature_quizzes">{tr}Take a quiz{/tr}</option>
<option value="tiki-quiz_stats.php,{tr}Quiz stats{/tr},feature_quizzes,tiki_p_view_quiz_stats">{tr}Quiz stats{/tr}</option>
<option value="tiki-quiz_stats_quiz.php?quizId=,{tr}Stats for a Quiz{/tr},feature_quizzes,tiki_p_view_quiz_stats">{tr}Stats for a Quiz{/tr}</option>
</select>
</td></tr></table>
</td></tr></table>
</td>
<td valign="top">
<h2>{tr}Preview menu{/tr}</h2>
<div class="box">
<div class="box-title">{$menu_info.name}</div>
<div class="box-data">
{include file=tiki-user_menu.tpl channels=$allchannels}
</div>
</div>
</td></tr></table>

<a name="options"></a>
<h2>{tr}Menu options{/tr}</h2>
<div align="center">
<form method="get" action="tiki-admin_menu_options.php">
<table class="findtable">
<tr><td>{tr}Find{/tr}</td>
   <td>
     <input type="text" name="find" value="{$find|escape}" />
     <input type="hidden" name="sort_mode" value="{$sort_mode|escape}" />
     <input type="hidden" name="menuId" value="{$menuId}" />
   </form>
   </td>
<td>{tr}Number of lines{/tr}</td><td><input type="text" name="nbRecords" value="{if !empty($nbRecords)}{$nbRecords}{else}{$maxRecords}{/if}" size="3" /></td>
<td><input type="submit" value="{tr}find{/tr}" name="search" /></td>
</tr>
</table>

<form method="get" action="tiki-admin_menu_options.php">
<input type="hidden" name="find" value="{$find|escape}" />
<input type="hidden" name="sort_mode" value="{$sort_mode|escape}" />
<input type="hidden" name="menuId" value="{$menuId}" />
<input type="hidden" name="offset" value="{$offset}" />
<table class="normal">
<tr>
<td class="heading"></td>
<td class="heading"><a class="tableheading" href="tiki-admin_menu_options.php?menuId={$menuId}&amp;offset={$offset}&amp;sort_mode={if $sort_mode eq 'optionId_desc'}optionId_asc{else}optionId_desc{/if}{if !empty($nbRecords)}&amp;nbRecords={$nbRecords}{/if}">{tr}ID{/tr}</a></td>
<td class="heading">&nbsp;</td>
<td class="heading"><a class="tableheading" href="tiki-admin_menu_options.php?menuId={$menuId}&amp;offset={$offset}&amp;sort_mode={if $sort_mode eq 'position_desc'}position_asc{else}position_desc{/if}{if !empty($nbRecords)}&amp;nbRecords={$nbRecords}{/if}">{tr}position{/tr}</a></td>
<td class="heading"><a class="tableheading" href="tiki-admin_menu_options.php?menuId={$menuId}&amp;offset={$offset}&amp;sort_mode={if $sort_mode eq 'name_desc'}name_asc{else}name_desc{/if}{if !empty($nbRecords)}&amp;nbRecords={$nbRecords}{/if}">{tr}name{/tr}</a></td>
<td class="heading"><a class="tableheading" href="tiki-admin_menu_options.php?menuId={$menuId}&amp;offset={$offset}&amp;sort_mode={if $sort_mode eq 'url_desc'}url_asc{else}url_desc{/if}{if !empty($nbRecords)}&amp;nbRecords={$nbRecords}{/if}">{tr}url{/tr}</a></td>
<td class="heading"><a class="tableheading" href="tiki-admin_menu_options.php?menuId={$menuId}&amp;offset={$offset}&amp;sort_mode={if $sort_mode eq 'type_desc'}type_asc{else}type_desc{/if}{if !empty($nbRecords)}&amp;nbRecords={$nbRecords}{/if}">{tr}type{/tr}</a></td>
<td class="heading"><a class="tableheading" href="tiki-admin_menu_options.php?menuId={$menuId}&amp;offset={$offset}&amp;sort_mode={if $sort_mode eq 'section_desc'}section_asc{else}section_desc{/if}{if !empty($nbRecords)}&amp;nbRecords={$nbRecords}{/if}">{tr}sections{/tr}</a></td>
<td class="heading"><a class="tableheading" href="tiki-admin_menu_options.php?menuId={$menuId}&amp;offset={$offset}&amp;sort_mode={if $sort_mode eq 'perm_desc'}perm_asc{else}perm_desc{/if}{if !empty($nbRecords)}&amp;nbRecords={$nbRecords}{/if}">{tr}permissions{/tr}</a></td>

<td class="heading"><a class="tableheading" href="tiki-admin_menu_options.php?menuId={$menuId}&amp;offset={$offset}&amp;sort_mode={if $sort_mode eq 'groupnam_desc'}groupname_asc{else}groupname_desc{/if}{if !empty($nbRecords)}&amp;nbRecords={$nbRecords}{/if}">{tr}group{/tr}</a></td>
<td class="heading">{tr}action{/tr}</td>
</tr>

{cycle values="odd,even" print=false}
{section name=user loop=$channels}
<tr>
<td class="{cycle advance=false}"><input type="checkbox" name="checked[]" value="{$channels[user].optionId|escape}"  {if $smarty.request.checked and in_array($channels[user].optionId,$smarty.request.checked)}checked="checked"{/if} /></td>
<td class="{cycle advance=false}">{$channels[user].optionId}</td>
<td class="{cycle advance=false}"><a class="link" href="tiki-admin_menu_options.php?menuId={$menuId}&amp;offset={$offset}&amp;sort_mode={$sort_mode}&amp;optionId={$channels[user].optionId}{if !empty($nbRecords)}&amp;nbRecords={$nbRecords}{/if}"
title="{tr}Edit{/tr}"><img src="pics/icons/page_edit.png" border="0" width="16" height="16"  alt='{tr}Edit{/tr}' /></a></td>
<td class="{cycle advance=false}">{$channels[user].position}</td>
<td class="{cycle advance=false}">{$channels[user].name}</td>
<td class="{cycle advance=false}"><a href="{$channels[user].url|escape}" class="link" target="_new">{$channels[user].url|truncate:40:' ...'}</a></td>
<td class="{cycle advance=false}">{$channels[user].type_description}</td>
<td class="{cycle advance=false}">{$channels[user].section}</td>
<td class="{cycle advance=false}">{$channels[user].perm}</td>
<td class="{cycle advance=false}">{$channels[user].groupname}</td>
<td class="{cycle advance=false}">
{if $channels[user].position > 1 }
<a class="link" href="tiki-admin_menu_options.php?menuId={$menuId}&amp;offset={$offset}&amp;sort_mode={$sort_mode}&amp;up={$channels[user].optionId}{if !empty($nbRecords)}&amp;nbRecords={$nbRecords}{/if}#options"
title="{tr}switch with previous option{/tr}"><img src="pics/icons/up.png" border="0" height="8" width="16" alt='{tr}up{/tr}' /></a>
{/if}
{if !$smarty.section.user.last}<a class="link" href="tiki-admin_menu_options.php?menuId={$menuId}&amp;offset={$offset}&amp;sort_mode={$sort_mode}&amp;down={$channels[user].optionId}{if !empty($nbRecords)}&amp;nbRecords={$nbRecords}{/if}#options"
title="{tr}switch with next option{/tr}"><img src="pics/icons/down.png" border="0" height="8" width="16" alt='{tr}down{/tr}' /></a>{/if}
&nbsp;&nbsp;<a class="link" href="tiki-admin_menu_options.php?menuId={$menuId}&amp;offset={$offset}&amp;sort_mode={$sort_mode}&amp;remove={$channels[user].optionId}{if !empty($nbRecords)}&amp;nbRecords={$nbRecords}{/if}"
title="{tr}Delete{/tr}"><img src="pics/icons/cross.png" border="0" height="16" width="16" alt='{tr}Delete{/tr}' /></a>
</td>
</tr>
{/section}
	<script type="text/javascript"> /* <![CDATA[ */
	document.write("<tr><td colspan=\"11\"><input name=\"switcher\" id=\"clickall\" type=\"checkbox\" onclick=\"switchCheckboxes(this.form,'checked[]',this.checked)\"/>");
	document.write("<label for=\"clickall\">{tr}select all{/tr}</label></td></tr>");
	/* ]]> */</script>
</table>
<div align="left">
{tr}Perform action with checked:{/tr}
<input type="image" name="delsel" src='pics/icons/cross.png' alt={tr}Delete{/tr}' title='{tr}Delete{/tr}' />
</div>
</form>
<br />

<div class="mini">
{if $prev_offset >= 0}
[<a class="prevnext" href="tiki-admin_menu_options.php?find={$find}&amp;menuId={$menuId}&amp;offset={$prev_offset}&amp;sort_mode={$sort_mode}{if !empty($nbRecords)}&amp;nbRecords={$nbRecords}{/if}">{tr}prev{/tr}</a>]&nbsp;
{/if}
{tr}Page{/tr}: {$actual_page}/{$cant_pages}
{if $next_offset >= 0}
&nbsp;[<a class="prevnext" href="tiki-admin_menu_options.php?find={$find}&amp;menuId={$menuId}&amp;offset={$next_offset}&amp;sort_mode={$sort_mode}{if !empty($nbRecords)}&amp;nbRecords={$nbRecords}{/if}">{tr}next{/tr}</a>]
{/if}
{if $direct_pagination eq 'y'}
<br />
{section loop=$cant_pages name=foo}
{assign var=selector_offset value=$smarty.section.foo.index|times:$maxRecords}
<a class="prevnext" href="tiki-admin_menu_options.php?find={$find}&amp;menuId={$menuId}&amp;offset={$selector_offset}&amp;sort_mode={$sort_mode}{if !empty($nbRecords)}&amp;nbRecords={$nbRecords}{/if}">
{$smarty.section.foo.index_next}</a>&nbsp;
{/section}
{/if}
</div>
</div>

{*
<form action="tiki-admin_menu_options.php" method="post">
<textarea name="menudump" cols="70" rows="42">{$menudump}</textarea><br />
<input type="submit" name="action" value="{tr}Save{/tr}" />
</form>
*}
