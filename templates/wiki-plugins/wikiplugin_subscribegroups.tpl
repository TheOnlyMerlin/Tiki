{* $Id$ *}
{if !empty($userGroups)}
{cycle values="odd,even" print=false}
<table class="normal">
{foreach from=$userGroups key=gr item=type}
	<tr class="{cycle}">
	<td>
		{if !empty($allGroups.$gr.groupHome)}<a href="{$allGroups.$gr.groupHome|escape:url}">{/if}
		{if $type eq 'included'}<i>{$gr|escape}</i>{else}{$gr|escape}{/if}
		{if !empty($allGroups.$gr.groupHome)}</a>{/if}
		{if $showdefault eq 'y' and $default_group eq $gr}{icon _id='group' alt="{tr}Your default group{/tr}"}{/if}
		{if $showgroupdescription eq 'y'}<div style="margin-left:10px">{$allGroups.$gr.groupDesc|escape}</div>{/if}
	</td>
	<td>
		{if $type ne 'included' and $allGroups.$gr.userChoice eq 'y'}
			<a href="{$smarty.server.REQUEST_URI}{if strstr($smarty.server.REQUEST_URI, '?')}&amp;{else}?{/if}unassign={$gr|escape:'url'}">{icon _id='cross' alt="{tr}Unsubscribe{/tr}"}</a>
		{/if}
		{if $showdefault eq 'y' and ($default_group ne $gr or !empty($defaulturl))}
			<a href="{$smarty.server.REQUEST_URI}{if strstr($smarty.server.REQUEST_URI, '?')}&amp;{else}?{/if}default={$gr|escape:'url'}" title="{tr}Change default group{/tr}">{icon _id='group' alt="{tr}Change default group{/tr}"}</a>
		{/if}
	</td>
	</tr>
{/foreach}
</table>
{/if}

{if $showsubscribe ne 'n' && !empty($possibleGroups) && $subscribestyle eq 'dropdown'}
<form method="post">
<select name="assign" onchange="this.form.submit();">
<option value=""><i>{if !empty($subscribe)}{$subscribe|escape}{else}{tr}Subscribe to a group{/tr}{/if}</i></option>
{foreach from=$possibleGroups item=gr}
	<option value="{$gr|escape}">
		{$gr|escape}
		{if $showgroupdescription eq 'y' and !empty($allGroups.$gr.groupDesc)} ({$allGroups.$gr.groupDesc|escape}){/if}
	</option>
{/foreach}
</select>
</form>
{elseif $showsubscribe ne 'n' && !empty($possibleGroups) && $subscribestyle eq 'table'}
<form method="post">
<table class="normal">
{foreach from=$possibleGroups item=gr}
	<tr>
	<td class="{cycle}">
	<input name="assign[]" type="checkbox" value="{$gr|escape}" /> {$gr|escape}
	{if $showgroupdescription eq 'y'}<div style="margin-left:20px">{$allGroups.$gr.groupDesc|escape}</div>{/if}
	</td>
	</tr>
{/foreach}
</table>
<input type="submit" value="{if !empty($subscribe)}{$subscribe|escape}{else}{tr}Subscribe to groups{/tr}{/if}" />
</form>
{/if}
