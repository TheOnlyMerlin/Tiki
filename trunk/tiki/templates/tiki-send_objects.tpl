<a class="pagetitle" href="tiki-send_objects.php">{tr}Send objects{/tr}</a><br/><br/>
{if $msg}
<div class="cbox">
<div class="cbox-title">
{tr}Transmission results{/tr}
</div>
<div class="cbox-data">
{$msg}
</div>
</div>
{/if}
<br/>
<div class="cbox">
<div class="cbox-title">
{tr}Send objects to this site{/tr}
</div>
<div class="cbox-data">
<form method="post" action="tiki-send_objects.php">
<input type="hidden" name="sendpages" value="{$form_sendpages}" />
<table>
<tr><td class="form">{tr}site{/tr}:</td><td class="form"><input type="text" name="site" value="{$site}" /></td></tr>
<tr><td class="form">{tr}path{/tr}:</td><td class="form"><input type="text" name="path" value="{$path}" /></td></tr>
<tr><td class="form">{tr}username{/tr}:</td><td class="form"><input type="text" name="username" value="{$username}" /></td></tr>
<tr><td class="form">{tr}password{/tr}:</td><td class="form"><input type="password" name="password" value="{$password}" /></td></tr>
<tr><td align="center" colspan="2" class="form"><input type="submit" name="send" value="{tr}send{/tr}" /></td></tr>
</table>
</form>
</div>
</div>
<br/>
<div class="cbox">
<div class="cbox-title">
{tr}Send Wiki Pages{/tr}
</div>
<div class="cbox-data">
<div class="simplebox">
<b>Pages</b>: 
{section name=ix loop=$sendpages}
{$sendpages[ix]}&nbsp;
{/section}
</div>
<form action="tiki-send_objects.php" method="post">
<input type="hidden" name="sendpages" value="{$form_sendpages}" />
<input type="hidden" name="site" value="{$site}" />
<input type="hidden" name="path" value="{$path}" />
<input type="hidden" name="username" value="{$username}" />
<input type="hidden" name="password" value="{$password}" />
<select name="pageName">
{section name=ix loop=$pages}
<option value="{$pages[ix].pageName}">{$pages[ix].pageName}</option>
{/section}
</select>
<input type="submit" name="addpage" value="{tr}add page{/tr}" />
<input type="submit" name="clearpages" value="{tr}clear{/tr}" />
</form>
</div>
</div>