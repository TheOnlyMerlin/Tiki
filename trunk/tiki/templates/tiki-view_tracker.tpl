<a class="pagetitle" href="tiki-view_tracker.php?trackerId={$trackerId}">{tr}Tracker{/tr}: {$tracker_info.name}</a><br /><br />
<div>
<span class="button2"><a href="tiki-list_trackers.php" class="linkbut">{tr}List trackers{/tr}</a></span>
{if $user}
<span class="button2"><a href="tiki-view_tracker.php?trackerId={$trackerId}&amp;monitor=1" class="linkbut">{tr}{$email_mon}{/tr}</a></span>
{/if}
{if $tiki_p_admin_trackers eq 'y'}
&nbsp;&nbsp;
<span class="button2"><a href="tiki-admin_trackers.php" class="linkbut">{tr}Admin trackers{/tr}</a></span>
<span class="button2"><a href="tiki-admin_trackers.php?trackerId={$trackerId}" class="linkbut">{tr}Edit this tracker{/tr}</a></span>
<span class="button2"><a href="tiki-admin_tracker_fields.php?trackerId={$trackerId}" class="linkbut">{tr}Edit fields{/tr}</a></span>
{/if}
</div>
<br />
<div class="wikitext">{$tracker_info.description}</div>
{if $mail_msg}
<div class="wikitext">{$mail_msg}</div>
{/if}
<br />

{cycle name=tabs values="1,2,3" print=false advance=false}
<div class="tabs">
<span id="tab{cycle name=tabs}" class="tab tabActive">{tr}Tracker{/tr} <i>{$tracker_info.name}</i></span>
{if $tiki_p_create_tracker_items eq 'y'}
<span id="tab{cycle name=tabs}" class="tab">{tr}Insert new item{/tr}</span>
{/if}
</div>

{cycle name=content values="1,2,3" print=false advance=false}

{* -------------------------------------------------- tab with list --- *}
<div id="content{cycle name=content}" class="content">

{if (($tracker_info.showStatus eq 'y' and $tracker_info.showStatusAdminOnly ne 'y') or $tiki_p_admin_trackers eq 'y') or $show_filters eq 'y'}
<form action="tiki-view_tracker.php" method="post">
<input type="hidden" name="trackerId" value="{$trackerId|escape}" />
<table class="normal"><tr>
{if $show_filters eq 'y'}
<td class="formcolor" style="width:100%;"><input type="text" name="filtervalue" value="{$filtervalue}" /></td>
<td>
<select name="filterfield">
{section name=ix loop=$fields}
{if $fields[ix].isTblVisible eq 'y' and $fields[ix].isSearchable eq 'y' and $fields[ix].type ne 'f' and $fields[ix].type ne 'j' and $fields[ix].type ne 'i'}
<option value="{$fields[ix].name|escape}">{$fields[ix].name}</option>
{/if}
{/section}
</select>
</td>
{/if}
{if ($tracker_info.showStatus eq 'y' and $tracker_info.showStatusAdminOnly ne 'y') or $tiki_p_admin_trackers eq 'y'}
<td>
<select name="status">
<option value="" {if $status eq ''}selected="selected"{/if}>{tr}any{/tr}</option>
{foreach key=st item=stdata from=$status_types}
<option value="{$st}"{if $status eq $st} selected="selected"{/if}>{$stdata.label}</option>
{/foreach}
</select>
</td>
{/if}
<td><input type="submit" name="filter" value="{tr}filter{/tr}" /></td>
</tr></table>
</form>
{/if}

{if $cant_pages > 1 or $initial}
<div align="center">
{section name=ini loop=$initials}
{if $initial and $initials[ini] eq $initial}
<span class="button2"><span class="linkbuton">{$initials[ini]|capitalize}</span></span> . 
{else}
<a href="tiki-view_tracker.php?initial={$initials[ini]}&amp;trackerId={$trackerId}{if $sort_mode}&amp;sort_mode={$sort_mode}{/if}{if $status}&amp;status={$status|escape:"url"}{/if}" 
class="prevnext">{$initials[ini]}</a> . 
{/if}
{/section}
<a href="tiki-view_tracker.php?initial=&amp;trackerId={$trackerId}{if $sort_mode}&amp;sort_mode={$sort_mode}{/if}{if $status}&amp;status={$status|escape:"url"}{/if}" 
class="prevnext">{tr}All{/tr}</a>
</div>
{/if}

<table class="normal">
<tr>
{if $tracker_info.showStatus eq 'y' or ($tracker_info.showStatusAdminOnly eq 'y' and $tiki_p_admin_trackers eq 'y')}
<th style="width:20px;">&nbsp;</th>
{/if}
{section name=ix loop=$fields}
{if $fields[ix].type eq 'l'}
<td class="heading auto">{$fields[ix].name|default:"&nbsp;"}</td>
{elseif $fields[ix].isTblVisible eq 'y' and $fields[ix].type ne 'x' and $fields[ix].type ne 'h'}
<td class="heading auto"><a class="tableheading" href="tiki-view_tracker.php?{if $status}status={$status}&amp;{/if}trackerId={$trackerId}&amp;offset={$offset}{section name=x loop=$fields}{if
$fields[x].value}&amp;{$fields[x].name|escape:"url"}={$fields[x].value|escape:"url"}{/if}{/section}&amp;sort_mode=f_{if $sort_mode eq
'f_'|cat:$fields[ix].name|cat:'_asc'}{$fields[ix].name|escape:"url"}_desc{else}{$fields[ix].name|escape:"url"}_asc{/if}">{$fields[ix].name|default:"&nbsp;"}</a></th>
{/if}
{/section}
{if $tracker_info.showCreated eq 'y'}
<th><a class="tableheading" href="tiki-view_tracker.php?{if $status}status={$status}&amp;{/if}{if $find}find={$find}&amp;{/if}trackerId={$trackerId}&amp;offset={$offset}&amp;sort_mode={if $sort_mode eq 'created_desc'}created_asc{else}created_desc{/if}">{tr}created{/tr}</a></th>
{/if}
{if $tracker_info.showLastModif eq 'y'}
<th><a class="tableheading" href="tiki-view_tracker.php?status={$status}&amp;find={$find}&amp;trackerId={$trackerId}&amp;offset={$offset}{section name=ix loop=$fields}{if $fields[ix].value}&amp;{$fields[ix].name}={$fields[ix].value}{/if}{/section}&amp;sort_mode={if $sort_mode eq 'lastModif_desc'}lastModif_asc{else}lastModif_desc{/if}">{tr}lastModif{/tr}</a></th>
{/if}
{if $tracker_info.useComments eq 'y' and $tracker_info.showComments eq 'y'}
<th width="5%">{tr}coms{/tr}</th>
{/if}
{if $tracker_info.useAttachments eq 'y' and  $tracker_info.showAttachments eq 'y'}
<th width="5%">{tr}atts{/tr}</th>
{/if}
{if $tiki_p_admin_trackers eq 'y'}
<th width="5%">&nbsp;</th>
{/if}
</tr>
{cycle values="odd,even" print=false}
{section name=user loop=$items}
<tr class="{cycle}">
{if $tracker_info.showStatus eq 'y' or ($tracker_info.showStatusAdminOnly eq 'y' and $tiki_p_admin_trackers eq 'y')}
<td class="auto" style="width:20px;">
{assign var=ustatus value=$items[user].status|default:"c"}
{html_image file=$status_types.$ustatus.image title=$status_types.$ustatus.label alt=$status_types.$ustatus.label}
</td>
{/if}
{section name=ix loop=$items[user].field_values}
{if $items[user].field_values[ix].isTblVisible eq 'y'}

{if $items[user].field_values[ix].type eq 'l'}
<td class="auto">
{foreach key=tid item=tlabel from=$items[user].field_values[ix].links}
<div><a href="tiki-view_tracker_item.php?trackerId={$items[user].field_values[ix].trackerId}&amp;itemId={$tid}" class="link">{$tlabel}</a></div>
{/foreach}
</td>
{elseif $items[user].field_values[ix].isMain eq 'y' or ($items[user].field_values[ix].linkId and $items[user].field_values[ix].trackerId)}
<td class="auto">
{if $items[user].field_values[ix].linkId and $items[user].field_values[ix].trackerId}
<a href="tiki-view_tracker_item.php?trackerId={$items[user].field_values[ix].trackerId}&amp;itemId={$items[user].field_values[ix].linkId}" class="link">
{elseif $tiki_p_view_trackers eq 'y' or $tiki_p_modify_tracker_items eq 'y' or $tiki_p_comment_tracker_items eq 'y'}
<a class="tablename" href="tiki-view_tracker_item.php?trackerId={$trackerId}{section name=mix loop=$fields}{if 
$fields[mix].value}&amp;{$fields[mix].name}={$fields[mix].value}{/if}{/section}&amp;itemId={$items[user].itemId}&amp;show=comm">
{/if}
{if $items[user].field_values[ix].type eq 'f'}
{$items[user].field_values[ix].value|tiki_short_datetime|default:"&nbsp;"}
{elseif $items[user].field_values[ix].type eq 'c'}
{$items[user].field_values[ix].value|replace:"y":"Yes"|replace:"n":"No"}
{elseif $items[user].field_values[ix].type eq 'i'}
<img src="{$items[user].field_values[ix].value}" alt="" />
{else}
{$items[user].field_values[ix].value|default:"&nbsp;"}
{/if}
{if $tiki_p_view_trackers eq 'y' or $tiki_p_modify_tracker_items eq 'y' or $tiki_p_comment_tracker_items eq 'y' or $items[user].field_values[ix].linkId}</a>{/if}
</td>
{else}
{if $items[user].field_values[ix].type eq 'f' or $items[user].field_values[ix].type eq 'j'}
<td class="auto">
{$items[user].field_values[ix].value|tiki_short_datetime|default:"&nbsp;"}
</td>
{elseif $items[user].field_values[ix].type ne 'x' and $items[user].field_values[ix].type ne 'h'}
<td class="auto">
{$items[user].field_values[ix].value|default:"&nbsp;"}
</td>
{/if}
{/if}
{/if}
{/section}
{if $tracker_info.showCreated eq 'y'}
<td>{$items[user].created|tiki_short_datetime}</td>
{/if}
{if $tracker_info.showLastModif eq 'y'}
<td>{$items[user].lastModif|tiki_short_datetime}</td>
{/if}
{if $tracker_info.useComments eq 'y' and $tracker_info.showComments eq 'y'}
<td  style="text-align:center;">{$items[user].comments}</td>
{/if}
{if $tracker_info.useAttachments eq 'y' and $tracker_info.showAttachments eq 'y'}
<td  style="text-align:center;"><a href="tiki-view_tracker_item.php?trackerId={$trackerId}{section name=mix loop=$fields}{if
$fields[mix].value}&amp;{$fields[mix].name}={$fields[mix].value}{/if}{/section}&amp;itemId={$items[user].itemId}&amp;show=att" link="{tr}List Attachments{/tr}"><img src="img/icons/folderin.gif" border="0" alt="{tr}List Attachments{/tr}" 
/></a>{$items[user].attachments}</td>
{/if}
{if $tiki_p_admin_trackers eq 'y'}
<td><a class="link" href="tiki-view_tracker.php?status={$status}&amp;trackerId={$trackerId}&amp;offset={$offset}&amp;sort_mode={$sort_mode}{section name=mix loop=$fields}{if $fields[mix].value}&amp;{$fields[mix].name}={$fields[mix].value}{/if}{/section}&amp;remove={$items[user].itemId}" 
onclick="return confirmTheLink(this,'{tr}Are you sure you want to delete this tracker item?{/tr}');" 
title="{tr}Click here to delete this tracker{/tr}"><img border="0" alt="{tr}Remove{/tr}" src="img/icons2/delete.gif" /></a></td>
{/if}
</tr>
{/section}
</table>
{if $cant_pages > 1 or $initial}
<br />
<div align="center" class="mini">
{if $prev_offset >= 0}
[<a class="prevnext" href="tiki-view_tracker.php?initial={$initial}&amp;status={$status}&amp;find={$find}&amp;trackerId={$trackerId}&amp;offset={$prev_offset}&amp;sort_mode={$sort_mode}{section name=ix loop=$fields}{if $fields[ix].value}&amp;{$fields[ix].name}={$fields[ix].value}{/if}{/section}">{tr}prev{/tr}</a>]&nbsp;
{/if}
{tr}Page{/tr}: {$actual_page}/{$cant_pages}
{if $next_offset >= 0}
&nbsp;[<a class="prevnext" href="tiki-view_tracker.php?initial={$initial}&amp;status={$status}&amp;find={$find}&amp;trackerId={$trackerId}&amp;offset={$next_offset}&amp;sort_mode={$sort_mode}{section name=ix loop=$fields}{if $fields[ix].value}&amp;{$fields[ix].name}={$fields[ix].value}{/if}{/section}">{tr}next{/tr}</a>]
{/if}
{if $direct_pagination eq 'y'}
<br />
{section loop=$cant_pages name=foo}
{assign var=selector_offset value=$smarty.section.foo.index|times:$maxRecords}
<a class="prevnext" href="tiki-view_tracker.php?initial={$initial}&amp;status={$status}&amp;find={$find}&amp;trackerId={$trackerId}&amp;offset={$selector_offset}&amp;sort_mode={$sort_mode}{section name=ix loop=$fields}{if $fields[ix].value}&amp;{$fields[ix].name}={$fields[ix].value}{/if}{/section}">
{$smarty.section.foo.index_next}</a>&nbsp;
{/section}
{/if}
</div>
{/if}
</div>

{* --- tab with edit --- *}
{if $tiki_p_create_tracker_items eq 'y'}
<div id="content{cycle name=content}" class="content">
<form action="tiki-view_tracker.php" method="post">
<input type="hidden" name="trackerId" value="{$trackerId|escape}" />
<input type="hidden" name="itemId" value="{$itemId|escape}" />
{section name=ix loop=$fields}
<input type="hidden" name="{$fields[ix].name|escape}" value="{$fields[ix].value|escape}" />
{/section}

<h3>{tr}Insert new item{/tr}</h3>
<table class="normal">
{section name=ix loop=$fields}

{if $fields[ix].type ne 'x' and $fields[ix].type ne 'l'}
{if $fields[ix].type eq 'h'}
</table>
<h3>{$fields[ix].name}</h3>
<table class="normal">
{else}
{if ($fields[ix].type eq 'c' or $fields[ix].type eq 't') and $fields[ix].options_array[0] eq '1'}
<tr class="formcolor"><td class="formlabel">{$fields[ix].name}</td><td nowrap="nowrap">
{elseif $stick eq 'y'}
<td class="formlabel right">{$fields[ix].name}</td><td nowrap="nowrap">
{else}
<tr class="formcolor"><td class="formlabel">{$fields[ix].name}</td><td colspan="3">
{/if}
{/if}

{if $fields[ix].type eq 'u'}
<select name="{$fields[ix].ins_id}">
<option value="">{tr}None{/tr}</option>
{foreach key=id item=one from=$users}
<option value="{$one|escape}">{$one}</option>
{/foreach}
</select>

{elseif $fields[ix].type eq 'g'}
<select name="{$fields[ix].ins_id}">
<option value="">{tr}None{/tr}</option>
{section name=ux loop=$groups}
<option value="{$groups[ux]|escape}">{$groups[ux]}</option>
{/section}
</select>

{elseif $fields[ix].type eq 'e'}
{assign var=fca value=$fields[ix].options}
<table width="100%"><tr>{cycle name=$fca values=",</tr><tr>" advance=false print=false}
{foreach key=ku item=iu from=$ins_fields[ix].$fca}
<td width="50%" nowrap="nowrap"><input type="checkbox" name="{$ku}[]" value="{$iu.categId}">{$iu.name}</td>{cycle name=$fca}
{/foreach}
</table>

{elseif $fields[ix].type eq 'i'}
<input type="file" name="{$fields[ix].ins_id}"/>

{elseif $fields[ix].type eq 't'}
<input type="text" name="{$fields[ix].ins_id}"{if $fields[ix].options_array[1]}size="{$fields[ix].options_array[1]}"{/if} />
{if $fields[ix].options_array[2]}<span class="formunit">&nbsp;{$fields[ix].options_array[2]}</span>{/if}

{elseif $fields[ix].type eq 'a'}
<textarea name="{$fields[ix].ins_id}" rows="4" cols="50"></textarea>

{elseif $fields[ix].type eq 'f'}
{html_select_date prefix=$fields[ix].ins_id time=$fields[ix].value end_year="+1"} {tr}at{/tr} {html_select_time prefix=$fields[ix].ins_id time=$fields[ix].value display_seconds=false}

{elseif $fields[ix].type eq 'd'}
<select name="{$fields[ix].name}">
{section name=jx loop=$fields[ix].options_array}
<option value="{$fields[ix].options_array[jx]|escape}" {if $fields[ix].value eq $fields[ix].options_array[jx]}selected="selected"{/if}>{$fields[ix].options_array[jx]}</option>
{/section}
</select>

{elseif $fields[ix].type eq 'c'}
<input type="checkbox" name="{$fields[ix].ins_id}" {if $fields[ix].value eq 'y'}checked="checked"{/if}/>

{elseif $fields[ix].type eq 'j'}
<input type="hidden" name="ins_{$fields[ix].ins_id}" value="" id="{$fields[ix].ins_id}" />
<span id="disp_{$fields[ix].ins_id}" class="daterow">{$fields[ix].value|default:$smarty.now|date_format:$daformat}</span>
<script type="text/javascript">
{literal}Calendar.setup( { {/literal}
date        : "{$fields[ix].value|default:$now|date_format:"%B %e, %Y %H:%M"}",      // initial date
inputField  : "{$fields[ix].ins_id}",      // ID of the input field
ifFormat    : "%s",    // the date format
displayArea : "disp_{$fields[ix].ins_id}",       // ID of the span where the date is to be shown
daFormat    : "{$daformat}",  // format of the displayed date
showsTime   : true,
singleClick : true,
align       : "bR"
{literal} } );{/literal}
</script>

{elseif $fields[ix].type eq 'r'}
<input type="text" name="{$fields[ix].ins_id}"{if $fields[ix].options_array[3]}size="{$fields[ix].options_array[3]}"{/if} />

{elseif $fields[ix].type eq 'l'}
<input type="text" name="{$fields[ix].ins_id}"{if $fields[ix].options_array[3]}size="{$fields[ix].options_array[3]}"{/if} />

{/if}


{if (($fields[ix].type eq 'c' or $fields[ix].type eq 't') and $fields[ix].options_array[0]) eq '1' and $stick ne 'y'}
</td>{assign var=stick value="y"}
{else}
</td></tr>{assign var=stick value="n"}
{/if}
{/if}
{/section}
<tr class="formcolor"><td>&nbsp;</td><td><input type="submit" name="save" value="{tr}save{/tr}" /></td></tr>
</table>
</form>
</div>
{/if}


