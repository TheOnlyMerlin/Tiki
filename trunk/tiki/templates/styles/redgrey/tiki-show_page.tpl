{* $Header: /cvsroot/tikiwiki/tiki/templates/styles/redgrey/tiki-show_page.tpl,v 1.8 2004-07-01 19:21:20 teedog Exp $ *}

{if $feature_page_title eq 'y'}<h1><a  href="tiki-index.php?page={$page|escape:"url"}" class="pagetitle">
  {if $structure eq 'y' and $page_info.page_alias ne ''}
    {$page_info.page_alias}
  {else}
    {$page}
  {/if}</a>
  {if $lock}
    <img src="img/icons/lock_topic.gif" alt="{tr}locked{/tr}" title="{tr}locked by{/tr} {$page_user}" />
  {/if}
  </h1>
{/if}
{if $feature_wiki_pageid eq 'y'}
	<small><a class="link" href="tiki-index.php?page_id={$page_id}">{tr}page id{/tr}: {$page_id}</a></small>
{/if}
<table class="wikitopline" width="100%">
  <tr>
    {if $feature_wiki_description eq 'y' or $cached_page eq 'y'}
      <td>
        {if $feature_wiki_description eq 'y'}<small>{$description}</small>{/if}
        {if $cached_page eq 'y'}<small>(cached)</small>{/if}
      </td>
    {/if}
  {if $is_categorized eq 'y' and $feature_categories eq 'y' and $feature_categorypath eq 'y'}
    <td align="right">{$display_catpath}</td>
    </tr><tr><td>&nbsp;</td>
  {/if}
  {if $print_page ne 'y'}
	<td style="text-align:right;">

	{if !$lock and ($tiki_p_edit eq 'y' or $page eq 'SandBox') and $beingEdited ne 'y'}
	  <a title="{tr}edit{/tr}" href="tiki-editpage.php?page={$page|escape:"url"}"><img style="border: 0" src="img/icons/edit.gif" alt='{tr}edit{/tr}' /></a>
	{/if}
	{if $cached_page eq 'y'}
	  <a title="{tr}refresh{/tr}" href="tiki-index.php?page={$page|escape:"url"}&amp;refresh=1"><img style="border: 0" src="img/icons/ico_redo.gif" alt='{tr}refresh{/tr}' /></a>
	{/if}

	<a title="{tr}print{/tr}" href="tiki-print.php?page={$page|escape:"url"}"><img style="border: 0" src="img/icons/ico_print.gif" alt='{tr}print{/tr}' /></a>

	{if $feature_wiki_pdf eq 'y'}
	  <a title="{tr}create pdf{/tr}" href="tiki-config_pdf.php?{if $home_info && $home_info.page_ref_id}page_ref_id={$home_info.page_ref_id}{else}page={$page|escape:"url"}{/if}"><img style="border: 0" src="img/icons/ico_pdf.gif" alt='{tr}pdf{/tr}' /></a>
	{/if}

	{if $user and $feature_notepad eq 'y' and $tiki_p_notepad eq 'y'}
	  <a title="{tr}Save to notepad{/tr}" href="tiki-index.php?page={$page|escape:"url"}&amp;savenotepad=1"><img style="border: 0" src="img/icons/ico_save.gif" alt="{tr}save{/tr}" /></a>
	{/if}

	{if $user and $feature_user_watches eq 'y'}
	  {if $user_watching_page eq 'n'}
          <a href="tiki-index.php?page={$page|escape:"url"}&amp;watch_event=wiki_page_changed&amp;watch_object={$page|escape:"url"}&amp;watch_action=add"><img border='0' alt='{tr}monitor this page{/tr}' title='{tr}monitor this page{/tr}' src='img/icons/icon_watch.png' /></a>
	  {else}
	    <a href="tiki-index.php?page={$page|escape:"url"}&amp;watch_event=wiki_page_changed&amp;watch_object={$page|escape:"url"}&amp;watch_action=remove"><img border='0' alt='{tr}stop monitoring this page{/tr}' title='{tr}stop monitoring this page{/tr}' src='img/icons/icon_unwatch.png' /></a>
	  {/if}
	{/if}
	
	{if $feature_backlinks eq 'y' and $backlinks}
	  <select name="page" onchange="go(this)">
	    <option value="tiki-index.php?page={$page|escape:"url"}">{tr}backlinks{/tr}...</option>
		{section name=back loop=$backlinks}
		  <option value="tiki-index.php?page={$backlinks[back].fromPage|escape:"url"}">{$backlinks[back].fromPage}</option>
		{/section}
	  </select>
	{/if}

        {if $wiki_feature_3d eq 'y'}
          <a title="{tr}3d browser{/tr}" href="javascript:wiki3d_open('{$page|escape}',{$wiki_3d_width}, {$wiki_3d_height})"><img border="0" src="img/icons/ico_wiki3d.gif" alt='{tr}3d browser{/tr}' /></a>
        {/if}

	{if count($showstructs) ne 0}
	  <select name="page" onchange="go(this)">
	    <option value="tiki-index.php?page={$page|escape:"url"}">{tr}Structures{/tr}...</option>
		{section name=struct loop=$showstructs}
		  <option value="tiki-index.php?page_ref_id={$showstructs[struct].req_page_ref_id}">
{if $showstructs[struct].page_alias} 
{$showstructs[struct].page_alias}
{else}
{$showstructs[struct].pageName}
{/if}</option>
		{/section}
	  </select>
	{/if}
	</td>
  {else}
    <td>&nbsp;</td>
  {/if}
  {if $feature_multilingual == 'y'}{include file="translated-lang.tpl" td='y'}{/if}
</tr>
</table>

<div class="wikitext"
{if $user_dbl eq 'y' and $feature_wiki_dblclickedit eq 'wikitext_only' and $tiki_p_edit eq 'y'}ondblclick="location.href='tiki-editpage.php?page={$page|escape:"url"}';"{/if}>
{if $structure eq 'y'}
<div class="tocnav">
<table>
<tr><td>
    {if $prev_info and $prev_info.page_ref_id}
		<a href="tiki-index.php?page_ref_id={$prev_info.page_ref_id}"><img src='img/icons2/nav_dot_right.gif' border='0' alt='{tr}Previous page{/tr}' 
   			{if $prev_info.page_alias}
   				title='{$prev_info.page_alias}'
   			{else}
   				title='{$prev_info.pageName}'
   			{/if}/></a>{else}<img src='img/icons2/8.gif' border='0'/>{/if}
	{if $parent_info}
   	<a href="tiki-index.php?page_ref_id={$parent_info.page_ref_id}"><img src='img/icons2/nav_home.gif' border='0' alt='{tr}Parent page{/tr}' 
        {if $parent_info.page_alias}
   	      title='{$parent_info.page_alias}'
        {else}
   	      title='{$parent_info.pageName}'
        {/if}/></a>{else}<img src='img/icons2/8.gif' border='0'/>{/if}
   	{if $next_info and $next_info.page_ref_id}
      <a href="tiki-index.php?page_ref_id={$next_info.page_ref_id}"><img src='img/icons2/nav_dot_left.gif' border='0' alt='{tr}Next page{/tr}' 
		  {if $next_info.page_alias}
			  title='{$next_info.page_alias}'
		  {else}
			  title='{$next_info.pageName}'
		  {/if}/></a>{else}<img src='img/icons2/8.gif' border='0'/>
	{/if}
	{if $home_info}
   	<a href="tiki-index.php?page_ref_id={$home_info.page_ref_id}"><img src='img/icons2/home.gif' border='0' alt='TOC' 
		  {if $home_info.page_alias}
			  title='{$home_info.page_alias}'
		  {else}
			  title='{$home_info.pageName}'
		  {/if}/></a>{/if}
  </td>
  <td>
    {section loop=$structure_path name=ix}
      {if $structure_path[ix].parent_id}->{/if}
	  <a href="tiki-index.php?page_ref_id={$structure_path[ix].page_ref_id}">
      {if $structure_path[ix].page_alias}
        {$structure_path[ix].page_alias}
	  {else}
        {$structure_path[ix].pageName}
	  {/if}
	  </a>
	{/section}
  </td>
</tr>
</table>
</div>
{/if}{$parsed}
{if $pages > 1}
	<br />
	<div align="center">
		<a href="tiki-index.php?{if $page_info}page_ref_id={$page_info.page_ref_id}{else}page={$page|escape:"url"}{/if}&amp;pagenum={$first_page}"><img src='img/icons2/nav_first.gif' border='0' alt='{tr}First page{/tr}' title='{tr}First page{/tr}' /></a>

		<a href="tiki-index.php?{if $page_info}page_ref_id={$page_info.page_ref_id}{else}page={$page|escape:"url"}{/if}&amp;pagenum={$prev_page}"><img src='img/icons2/nav_dot_right.gif' border='0' alt='{tr}Previous page{/tr}' title='{tr}Previous page{/tr}' /></a>

		<small>{tr}page{/tr}:{$pagenum}/{$pages}</small>

		<a href="tiki-index.php?{if $page_info}page_ref_id={$page_info.page_ref_id}{else}page={$page|escape:"url"}{/if}&amp;pagenum={$next_page}"><img src='img/icons2/nav_dot_left.gif' border='0' alt='{tr}Next page{/tr}' title='{tr}Next page{/tr}' /></a>


		<a href="tiki-index.php?{if $page_info}page_ref_id={$page_info.page_ref_id}{else}page={$page|escape:"url"}{/if}&amp;pagenum={$last_page}"><img src='img/icons2/nav_last.gif' border='0' alt='{tr}Last page{/tr}' title='{tr}Last page{/tr}' /></a>
	</div>
{/if}
</div> {* End of main wiki page *}

{if $has_footnote eq 'y'}<div class="wikitext">{$footnote}</div>{/if}

{if $tiki_p_edit eq 'y'}
<p class="editdate">
  {tr}Last modification{/tr}: {$lastModif|tiki_long_datetime} {tr}by{/tr} {$lastUser|userlink}
</p>
{/if}
{include file=tiki-page_bar.tpl}
{if $wiki_feature_copyrights  eq 'y' and $wikiLicensePage}
  {if $wikiLicensePage == $page}
    {if $tiki_p_edit_copyrights eq 'y'}
      <p class="editdate">{tr}To edit the copyright notices{/tr} <a href="copyrights.php?page={$copyrightpage}">{tr}click here{/tr}</a>.</p>
    {/if}
  {else}
    <p class="editdate">{tr}The content on this page is licensed under the terms of the{/tr} <a href="tiki-index.php?page={$wikiLicensePage}&amp;copyrightpage={$page|escape:"url"}">{$wikiLicensePage}</a>.</p>
  {/if}
{/if}

{if $print_page eq 'y'}
  <div class="editdate" align="center"><p>
    {tr}The original document is available at{/tr} {$urlprefix}tiki-index.php?page{$page|escape:"url"}
  </p></div>
{/if}
<br />
{if $is_categorized eq 'y' and $feature_categories eq 'y' and $feature_categoryobjects eq 'y'}
  {$display_catobjects}
{/if}
