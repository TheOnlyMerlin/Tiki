<a href="tiki-adminusers.php" class="pagetitle">{tr}Admin users{/tr}</a><br/><br/>
<h3>{tr}Add a new user{/tr}</h3>
<form action="tiki-adminusers.php" method="post">
<table class="normal">
<tr><td class="formcolor">{tr}User{/tr}:</td><td class="formcolor"><input type="text" name="name" /></td></tr>
<tr><td class="formcolor">{tr}Pass{/tr}:</td><td class="formcolor"><input type="password" name="pass" /></td></tr>
<tr><td class="formcolor">{tr}Again{/tr}:</td><td class="formcolor"><input type="password" name="pass2" /></td></tr>
<tr><td class="formcolor">{tr}Email{/tr}:</td><td class="formcolor"><input type="text" name="email" /></td></tr>
<tr><td  class="formcolor">&nbsp;</td><td class="formcolor"><input type="submit" name="newuser" value="{tr}Add{/tr}" /></td></tr>
</table>
</form>
<br/><br/>
<h3>{tr}Users{/tr}</h3>
<div  align="center">
<table class="findtable">
<tr><td class="findtable">Find</td>
   <td class="findtable">
   <form method="get" action="tiki-adminusers.php">
     <input type="text" name="find" />
     <input type="submit" value="find" name="search" />
     <input type="hidden" name="sort_mode" value="{$sort_mode}" />
   </form>
   </td>
</tr>
</table>
<table class="normal">
<tr>
<td class="heading"><a class="tableheading" href="tiki-adminusers.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'login_desc'}login_asc{else}login_desc{/if}">{tr}name{/tr}</a></td>
<td class="heading"><a class="tableheading" href="tiki-adminusers.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'email_desc'}email_asc{else}email_desc{/if}">{tr}email{/tr}</a></td>
<td class="heading"><a class="tableheading" href="tiki-adminusers.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'lastLogin_desc'}lastLogin_asc{else}lastLogin_desc{/if}">{tr}last_login{/tr}</a></td>
<td class="heading">{tr}Groups{/tr}</td>
<td class="heading">{tr}action{/tr}</td>
</tr>
{section name=user loop=$users}
{if $smarty.section.user.index % 2}
<tr>
<td class="odd">{$users[user].user}</td>
<td class="odd">{$users[user].email}</td>
<td class="odd">{$users[user].lastLogin|date_format:"%A %d of %B, %Y [%H:%M:%S]"}</td>
<td class="odd">
{section name=grs loop=$users[user].groups}
{$users[user].groups[grs]}{if $users[user].groups[grs] != "Anonymous"}(<a class="link" href="tiki-adminusers.php?offset={$offset}&amp;sort_mode={$sort_mode}&amp;ruser={$users[user].user}&amp;action=removegroup&amp;group={$users[user].groups[grs]}">x</a>){/if}&nbsp;
{/section}
</td>
<td class="odd"><a class="link" href="tiki-adminusers.php?offset={$offset}&amp;sort_mode={$sort_mode}&amp;action=delete&amp;user={$users[user].user}">{tr}delete{/tr}</a><br/>
                                   <a class="link" href="tiki-assignuser.php?assign_user={$users[user].user}">{tr}assign group{/tr}</a><br/>
                                   <a class="link" href="tiki-user_preferences.php?view_user={$users[user].user}">{tr}view info{/tr}</a>
                                   </td>
</tr>
{else}
<tr>
<td class="even">{$users[user].user}</td>
<td class="even">{$users[user].email}</td>
<td class="even">{$users[user].lastLogin|date_format:"%A %d of %B, %Y [%H:%M:%S]"}</td>
<td class="even">
{section name=grs loop=$users[user].groups}
{$users[user].groups[grs]}{if $users[user].groups[grs] != "Anonymous"}(<a class="link" href="tiki-adminusers.php?offset={$offset}&amp;sort_mode={$sort_mode}&amp;ruser={$users[user].user}&amp;action=removegroup&amp;group={$users[user].groups[grs]}">x</a>){/if}&nbsp;
{/section}
</td>
<td class="even"><a class="link" href="tiki-adminusers.php?offset={$offset}&amp;sort_mode={$sort_mode}&amp;action=delete&amp;user={$users[user].user}">{tr}delete{/tr}</a><br/>
                 <a class="link" href="tiki-assignuser.php?assign_user={$users[user].user}">{tr}assign group{/tr}</a><br/>
                 <a class="link" href="tiki-user_preferences.php?view_user={$users[user].user}">{tr}view info{/tr}</a></td>
</tr>
{/if}
{/section}
</table>
<br/>
<div class="mini">
{if $prev_offset >= 0}
[<a class="prevnext" href="tiki-adminusers.php?offset={$prev_offset}&amp;sort_mode={$sort_mode}">{tr}prev{/tr}</a>]&nbsp;
{/if}
{tr}Page{/tr}: {$actual_page}/{$cant_pages}
{if $next_offset >= 0}
&nbsp;[<a class="prevnext" href="tiki-adminusers.php?offset={$next_offset}&amp;sort_mode={$sort_mode}">{tr}next{/tr}</a>]
{/if}
</div>
</div>

