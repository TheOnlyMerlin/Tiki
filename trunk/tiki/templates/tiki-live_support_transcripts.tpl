<a href="tiki-live_support_transcripts.php" class="pagetitle">{tr}Support chat transcripts{/tr}</a><br/><br/>
<a class="link" href="tiki-live_support_admin.php">{tr}back to admin{/tr}</a>
<h2>{tr}Support requests{/tr}</h2>
<form method="get" action="tiki-live_support_transcripts.php">
<input type="hidden" name="sort_mode" value="{$sort_mode}" />
<table>
<tr>
	<td>{tr}find{/tr}</td>
	<td>{tr}user{/tr}</td>
	<td>{tr}op{/tr}</td>	
	<td>&nbsp;</td>
</tr>

<tr>
	<td><input type="text" name="find" value="{$find}" /></td>
	<td>
		<select name="filter_user">
			<option value="" {if $filter_user eq ''}selected="selected"{/if}>{tr}all{/tr}</option>
			{section name=ix loop=$users}
				<option value="{$users[ix]}" {if $users[ix] eq $filter_user}selected="selected"{/if}>{$users[ix]}</option>
			{/section}
		</select>
	</td>
	<td>
		<select name="filter_operator">
			<option value="" {if $filter_operator eq ''}selected="selected"{/if}>{tr}all{/tr}</option>
			{section name=ix loop=$operators}
				<option value="{$operators[ix]}" {if $operators[ix] eq $filter_operator}selected="selected"{/if}>{$users[ix]}</option>
			{/section}
		</select>
	</td>
	<td><input type="submit" value="{tr}find{/tr}" name="filter" /></td>
</tr>
</table>
</form>
<table class="normal">
<tr>
<td width="20%" class="heading"><a class="tableheading" href="tiki-live_support_transcripts.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'chat_started_desc'}chat_started_asc{else}chat_started_desc{/if}">{tr}started{/tr}</a></td>
<td class="heading"><a class="tableheading" href="tiki-live_support_transcripts.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'tiki_user_desc'}tiki_user_asc{else}tiki_user_desc{/if}">{tr}user{/tr}</a></td>
<td class="heading"><a class="tableheading" href="tiki-live_support_transcripts.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'reason_desc'}reason_asc{else}reason_desc{/if}">{tr}reason{/tr}</a></td>
<td class="heading"><a class="tableheading" href="tiki-live_support_transcripts.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'operator_desc'}operator_asc{else}operator_desc{/if}">{tr}op{/tr}</a></td>
<td width="2%" class="heading">{tr}msgs{/tr}</td>
</tr>
{cycle values="odd,even" print=false}
{section name=ix loop=$items}
<tr>
<td class="{cycle advance=false}">{$items[ix].chat_started|date_format:"%d %b [%H:%M]"}</td>
<td class="{cycle advance=false}">{$items[ix].tiki_user}</td>
<td class="{cycle advance=false}">{$items[ix].reason}</td>
<td class="{cycle advance=false}">{$items[ix].operator}</td>
<td style="text-align:right;" class="{cycle advance=false}"><a class="link" href="tiki-live_support_transcripts.php?offset={$offset}&amp;sort_mode={$sort_mode}&amp;find={$find}&amp;filter_user={$filter_user}&amp;filter_operator={$filter_operator}&amp;view={$items[ix].reqId}">{$items[ix].msgs}</a></td>
</tr>
{/section}
</table>

<div class="mini">
<div align="center">
{if $prev_offset >= 0}
[<a class="prevnext" href="tiki-live_support_transcripts.php?offset={$prev_offset}&amp;find={$find}&amp;sort_mode={$sort_mode}&amp;filter_user={$filter_user}&amp;filter_operator={$filter_operator}">{tr}prev{/tr}</a>]&nbsp;
{/if}
{tr}Page{/tr}: {$actual_page}/{$cant_pages}
{if $next_offset >= 0}
&nbsp;[<a class="prevnext" href="tiki-live_support_transcripts.php?offset={$next_offset}&amp;find={$find}&amp;sort_mode={$sort_mode}&amp;filter_user={$filter_user}&amp;filter_operator={$filter_operator}">{tr}next{/tr}</a>]
{/if}
{if $direct_pagination eq 'y'}
<br/>
{section loop=$cant_pages name=foo}
{assign var=selector_offset value=$smarty.section.foo.index|times:$maxRecords}
<a class="prevnext" href="tiki-live_support_transcripts.php?offset={$selector_offset}&amp;find={$find}&amp;sort_mode={$sort_mode}&amp;filter_user={$filter_user}&amp;filter_operator={$filter_operator}">
{$smarty.section.foo.index_next}</a>&nbsp;
{/section}
{/if}
</div>
</div> 

{if $smarty.request.view}
<h3>{tr}Transcript{/tr}</h3>
<table class="normal">
{section name=ix loop=$events}
<tr>
	<td width="15%" class="odd">
		{$events[ix].timestamp|tiki_short_time}
	</td>
	<td class="odd">
		{$events[ix].data}
	</td>
</tr>	
{/section}
</table>
{/if}
 