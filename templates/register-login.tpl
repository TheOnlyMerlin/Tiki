{if $prefs.user_register_prettytracker eq 'y' and $prefs.user_register_prettytracker_tpl}
	<input type="text" name="name" id="name" />
{else}
<tr>
	<td class="formcolor"><label for="name">{if $prefs.login_is_email eq 'y'}{tr}Email{/tr}{else}{tr}Username{/tr}{/if}:</label>{if $trackerEditFormId}&nbsp;<strong class='mandatory_star'>*</strong>&nbsp;{/if}</td>
	<td class="formcolor">
		<input type="text" name="name" id="name" {if $prefs.feature_ajax eq 'y'} onkeyup="return check_name()" onblur="return check_name()"{/if} />
		{if $prefs.feature_ajax eq 'y'}<span id="ajax_msg_name" style="vertical-align: middle;"></span>{/if}
			{if $prefs.login_is_email eq 'y'}
			<em>{tr}Use your email as login{/tr}</em>.
		{else}
			{if $prefs.min_username_length > 1}<div class="highlight"><em>{tr}Minimum {$prefs.min_username_length} characters long{/tr}</em></div>{/if}
			{if $prefs.lowercase_username eq 'y'}<div class="highlight"><em>{tr}Lowercase only{/tr}</em></div>{/if}
		{/if}
	</td>
</tr>
{/if}