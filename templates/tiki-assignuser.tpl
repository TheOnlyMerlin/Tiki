<!-- START of {$smarty.template} -->{* $Id$ *}

{assign var=escuser value=$assign_user|escape:url}
{title}{tr}Assign User {$assign_user} to Groups{/tr}{/title}

<div class="navbar">
	{if $tiki_p_admin eq 'y'} {* only full admins can manage groups, not tiki_p_admin_users *}
		{button href="tiki-admingroups.php" _text="{tr}Admin groups{/tr}"}
	{/if}
	{if $tiki_p_admin eq 'y' or $tiki_p_admin_users eq 'y'}
		{button href="tiki-adminusers.php" _text="{tr}Admin users{/tr}"}
	{/if}
</div>

{if $prefs.feature_intertiki eq 'y' and $prefs.feature_intertiki_import_groups eq 'y'}
	{remarksbox type="warning" title="{tr}Warning{/tr}"}
		{tr}Since this tiki site is in slave mode and import groups, the master groups will be automatically reimported at each login{/tr}
	{/remarksbox}
{/if}
  
<h2>{tr}User Information{/tr}</h2>
<table class="normal">
<tr><td class="even">{tr}Login{/tr}:</td><td class="odd">{$user_info.login}</td></tr>
<tr><td class="even">{tr}Email{/tr}:</td><td class="odd">{$user_info.email}</td></tr>
<tr><td class="even">{tr}Groups{/tr}:</td><td class="odd">
{foreach from=$user_info.groups item=what key=grp}
{if $what eq 'included'}<i>{/if}{$grp}{if $what eq 'included'}</i>{/if}
{if $grp != "Anonymous" && $grp != "Registered"}
<a class="link" href="tiki-assignuser.php?{if $offset}offset={$offset}&amp;{/if}maxRecords={$prefs.maxRecords}&amp;sort_mode={$sort_mode}{if $assign_user}&amp;assign_user={$assign_user|escape:url}{/if}&amp;action=removegroup&amp;group={$grp|escape:url}" title="Remove">{icon _id='cross' alt='{tr}Remove{/tr}' style="vertical-align:middle"}</a>{if !$user_info.groups.last},{/if}
{/if}&nbsp;&nbsp;
{/foreach}
</td></tr>
<form method="post" action="tiki-assignuser.php{if $assign_user}?assign_user={$assign_user}{/if}">
<tr><td class="even">{tr}Default Group{/tr}:</td><td class="odd">
<select name="defaultgroup">
<option value=""></option>
{foreach from=$user_info.groups key=name item=included}
<option value="{$name}" {if $name eq $user_info.default_group}selected="selected"{/if}>{$name}</option>
{/foreach}
</select>
<input type="hidden" value="{$user_info.login}" name="login" />
<input type="hidden" value="{$prefs.maxRecords}" name="maxRecords" />
<input type="hidden" value="{$offset}" name="offset" />
<input type="hidden" value="{$sort_mode}" name="sort_mode" />
<input type="submit" value="{tr}Set{/tr}" name="set_default" />
</form>
</td></tr>
</table>
<br />
<div align="left"><h2>{tr}Assign User {$assign_user} to Groups{/tr}</h2></div>

{include file='find.tpl' find_show_num_rows='y'}

<table class="normal">
<tr>
<th><a href="tiki-assignuser.php?{if $assign_user}assign_user={$assign_user|escape:url}&amp;{/if}offset={$offset}&amp;maxRecords={$prefs.maxRecords}&amp;sort_mode={if $sort_mode eq 'groupName_desc'}groupName_asc{else}groupName_desc{/if}">{tr}Name{/tr}</a></th>
<th><a href="tiki-assignuser.php?{if $assign_user}assign_user={$assign_user|escape:url}&amp;{/if}offset={$offset}&amp;maxRecords={$prefs.maxRecords}&amp;sort_mode={if $sort_mode eq 'groupDesc_desc'}groupDesc_asc{else}groupDesc_desc{/if}">{tr}Description{/tr}</a></th>
<th>{tr}Action{/tr}</td>
</tr>
{cycle values="even,odd" print=false}
{section name=user loop=$users}
{if $users[user].groupName != 'Anonymous'}
<tr>
<td class="{cycle advance=false}">
{if $tiki_p_admin eq 'y'}<a class="link" href="tiki-assignpermission.php?group={$users[user].groupName|escape:url}" title="{tr}Assign Perms to this Group{/tr}">{icon _id='key' align="right" alt="{tr}Permissions{/tr}"}</a>{/if}{$users[user].groupName}</td>
<td class="{cycle advance=false}">{tr}{$users[user].groupDesc}{/tr}</td>
<td class="{cycle}">
{if $users[user].what ne 'real'}
<a class="link" href="tiki-assignuser.php?{if $offset}offset={$offset}&amp;{/if}maxRecords={$prefs.maxRecords}&amp;sort_mode={$sort_mode}&amp;action=assign&amp;group={$users[user].groupName|escape:url}{if $assign_user}&amp;assign_user={$assign_user|escape:url}{/if}" title="{tr}Assign User to Group{/tr}">{icon _id='add' alt='{tr}Assign{/tr}'}</a>
{elseif $users[user].groupName ne "Registered"}
<a class="link" href="tiki-assignuser.php?{if $offset}offset={$offset}&amp;{/if}maxRecords={$prefs.maxRecords}&amp;sort_mode={$sort_mode}{if $assign_user}&amp;assign_user={$assign_user|escape:url}{/if}&amp;action=removegroup&amp;group={$users[user].groupName|escape:url}" title="unassign">{icon _id='cross' alt='{tr}Unassign{/tr}'}</a>
{/if}
</td></tr>
{/if}
{/section}
</table>

{pagination_links cant=$cant_pages step=$prefs.maxRecords offset=$offset}{/pagination_links}<!-- END of {$smarty.template} -->
