<h2>{tr}Register as a new user{/tr}</h2>
<br />
{if $showmsg eq 'y'}
{$msg}
{elseif $notrecognized eq 'y'}
{tr}Your email could not be validated; make sure you email is correct and click register below.{/tr}<br />
<form action="tiki-register.php" method="post">
<input type="text" name="email" value="{$email}"/>
<input type="hidden" name="name" value="{$login}"/>
<input type="hidden" name="pass" value="{$password}"/>
<input type="hidden" name="novalidation" value="yes"/>
<input type="submit" name="register" value="{tr}register{/tr}" />
</form>
{else}
{if $rnd_num_reg eq 'y'}
<small>{tr}Your registration code:{/tr}</small>
<img src="tiki-random_num_img.php" alt='{tr}Random Image{/tr}'/>
<br />
{/if}
<form action="tiki-register.php" method="post"> <br />
<table>
<tr><td>{tr}Username{/tr}:</td><td><input type="text" name="name" /></td></tr>
{if $useRegisterPasscode eq 'y'}
<tr><td>{tr}Passcode to register (not your user password){/tr}:</td><td><input type="password" name="passcode" /></td></tr>
{/if}
{if $rnd_num_reg eq 'y'}
<tr><td>{tr}Registration code{/tr}:</td>
<td><input type="text" maxlength="8" size="8" name="regcode" /></td></tr>
{/if}
<tr><td>{tr}Password{/tr}:</td><td><input id='pass1' type="password" name="pass" /></td></tr>
<tr><td>{tr}Repeat password{/tr}:</td><td><input id='pass2' type="password" name="pass2" /></td></tr>
<tr><td>{tr}Email{/tr}:</td><td><input type="text" name="email" /></td></tr>
<tr><td >&nbsp;</td><td><input type="submit" name="register" value="{tr}register{/tr}" /></td></tr>
</table>
</form>
<br />
<table>
<tr><td><a href="javascript:genPass('genepass','pass','pass2');">{tr}Generate a password{/tr}</a></td>
<td><input id='genepass' type="text" /></td></tr>
</table>
{/if}
