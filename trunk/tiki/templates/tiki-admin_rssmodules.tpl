<a class="pagetitle" href="tiki-admin_rssmodules.php">Admin RSS modules</a><br/><br/>
{if $preview eq 'y'}
<div class="simplebox">
<h2>{tr}Content for the feed{/tr}</h2>
<ul>
{section name=ix loop=$items}
<li><a href="{$items[ix].link}" class="link">{$items[ix].title}</a></li>
{/section}
</ul>
</div>
{/if}
<h2>{tr}Create/edit RSS module{/tr}</h2>
<form action="tiki-admin_rssmodules.php" method="post">
<input type="hidden" name="rssId" value="{$rssId}" />
<table class="normal">
<tr><td class="formcolor">{tr}Name{/tr}:</td><td class="formcolor"><input type="text" name="name" value="{$name}" /></td></tr>
<tr><td class="formcolor">{tr}Description{/tr}:</td><td class="formcolor"><textarea name="description" rows="4" cols="40">{$description}</textarea></td></tr>
<tr><td class="formcolor">{tr}URL{/tr}:</td><td class="formcolor"><input size="47" type="text" name="url" value="{$url}" /></td></tr>
<tr><td class="formcolor">{tr}Refresh rate{/tr}:</td><td class="formcolor">
<select name="refresh">
<option value="1" {if $refresh eq 1}selected="selected"{/if}>1 {tr}minute{/tr}</option>
<option value="5" {if $refresh eq 5}selected="selected"{/if}>5 {tr}minutes{/tr}</option>
<option value="10" {if $refresh eq 10}selected="selected"{/if}>10 {tr}minutes{/tr}</option>
<option value="15" {if $refresh eq 15}selected="selected"{/if}>15 {tr}minutes{/tr}</option>
<option value="20" {if $refresh eq 20}selected="selected"{/if}>20 {tr}minutes{/tr}</option>
<option value="30" {if $refresh eq 30}selected="selected"{/if}>30 {tr}minutes{/tr}</option>
<option value="45" {if $refresh eq 45}selected="selected"{/if}>45 {tr}minutes{/tr}</option>
<option value="60" {if $refresh eq 60}selected="selected"{/if}>1 {tr}hour{/tr}</option>
<option value="90" {if $refresh eq 90}selected="selected"{/if}>1.5 {tr}hours{/tr}</option>
<option value="120" {if $refresh eq 120}selected="selected"{/if}>2 {tr}hours{/tr}</option>
<option value="1440" {if $refresh eq 1440}selected="selected"{/if}>1 {tr}day{/tr}</option>
</select>
</td></tr>
<tr><td  class="formcolor">&nbsp;</td><td class="formcolor"><input type="submit" name="save" value="{tr}Save{/tr}" /></td></tr>
</table>
</form>
<h2>Rss channels</h2>
<div  align="center">
<table class="findtable">
<tr><td class="findtable">Find</td>
   <td class="findtable">
   <form method="get" action="tiki-admin_rssmodules.php">
     <input type="text" name="find" />
     <input type="submit" value="{tr}find{/tr}" name="search" />
     <input type="hidden" name="sort_mode" value="{$sort_mode}" />
   </form>
   </td>
</tr>
</table>
<table class="normal">
<tr>
<td class="heading"><a class="tableheading" href="tiki-admin_rssmodules.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'rssId_desc'}rssId_asc{else}rssId_desc{/if}">{tr}ID{/tr}</a></td>
<td class="heading"><a class="tableheading" href="tiki-admin_rssmodules.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'name_desc'}name_asc{else}name_desc{/if}">{tr}name{/tr}</a></td>
<td class="heading"><a class="tableheading" href="tiki-admin_rssmodules.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'description_desc'}description_asc{else}description_desc{/if}">{tr}description{/tr}</a></td>
<td class="heading"><a class="tableheading" href="tiki-admin_rssmodules.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'url_desc'}url_asc{else}url_desc{/if}">{tr}url{/tr}</a></td>
<td class="heading"><a class="tableheading" href="tiki-admin_rssmodules.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'lastUpdated_desc'}lastUpdated_asc{else}lastUpdated_desc{/if}">{tr}Last update{/tr}</a></td>
<td class="heading"><a class="tableheading" href="tiki-admin_rssmodules.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'refresh_desc'}refresh_asc{else}refresh_desc{/if}">{tr}refresh{/tr}</a></td>
<td class="heading">{tr}action{/tr}</td>
</tr>
{section name=user loop=$channels}
{if $smarty.section.user.index % 2}
<tr>
<td class="odd">{$channels[user].rssId}</td>
<td class="odd">{$channels[user].name}</td>
<td class="odd">{$channels[user].description}</td>
<td class="odd">{$channels[user].url}</td>
<td class="odd">{$channels[user].lastUpdated|date_format:"%a %d of %b, %Y [%H:%M:%S]"}</td>
<td class="odd">{$channels[user].minutes} min</td>
<td class="odd">
   <a class="link" href="tiki-admin_rssmodules.php?offset={$offset}&amp;sort_mode={$sort_mode}&amp;remove={$channels[user].rssId}">{tr}remove{/tr}</a>
   <a class="link" href="tiki-admin_rssmodules.php?offset={$offset}&amp;sort_mode={$sort_mode}&amp;rssId={$channels[user].rssId}">{tr}edit{/tr}</a>
   <a class="link" href="tiki-admin_rssmodules.php?offset={$offset}&amp;sort_mode={$sort_mode}&amp;view={$channels[user].rssId}">{tr}view{/tr}</a>
</td>
</tr>
{else}
<tr>
<td class="even">{$channels[user].rssId}</td>
<td class="even">{$channels[user].name}</td>
<td class="even">{$channels[user].description}</td>
<td class="even">{$channels[user].url}</td>
<td class="even">{$channels[user].lastUpdated|date_format:"%a %d of %b, %Y [%H:%M:%S]"}</td>
<td class="even">{$channels[user].minutes} min</td>
<td class="even">
   <a class="link" href="tiki-admin_rssmodules.php?offset={$offset}&amp;sort_mode={$sort_mode}&amp;remove={$channels[user].rssId}">{tr}remove{/tr}</a>
   <a class="link" href="tiki-admin_rssmodules.php?offset={$offset}&amp;sort_mode={$sort_mode}&amp;rssId={$channels[user].rssId}">{tr}edit{/tr}</a>
   <a class="link" href="tiki-admin_rssmodules.php?offset={$offset}&amp;sort_mode={$sort_mode}&amp;view={$channels[user].rssId}">{tr}view{/tr}</a>
</td>
</tr>
{/if}
{/section}
</table>
<br/>
<div class="mini">
{if $prev_offset >= 0}
[<a class="prevnext" href="tiki-admin_rssmodules.php?offset={$prev_offset}&amp;sort_mode={$sort_mode}">{tr}prev{/tr}</a>]&nbsp;
{/if}
{tr}Page{/tr}: {$actual_page}/{$cant_pages}
{if $next_offset >= 0}
&nbsp;[<a class="prevnext" href="tiki-admin_rssmodules.php?offset={$next_offset}&amp;sort_mode={$sort_mode}">{tr}next{/tr}</a>]
{/if}
</div>
</div>

