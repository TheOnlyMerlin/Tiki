<h1><a href="cc.php?page=my_tr&amp;new" class="pagetitle">Record a transaction {if $currency} in cc {$currency}{/if}</a>
</h1>

<span class="button2"><a href="cc.php" class="linkbut">{tr}Help{/tr}</a></span><br />
<br />

{if $msg}<div class="simplebox">{$msg}</div>{/if}

<form action="cc.php?page=my_tr" method="post">
<table class="formcolor">

<tr class="formrow">
<td>description</td>
{if $tiki_p_cc_admin eq 'y'}
<td>from account</td>	
{/if}
<td>to account</td>	
<td align=right>amount</td>
<td>cc</td>
</tr>

<tr class="formrow">
<td><input type='text' name='tr_item' size="24" value="{$smarty.request.tr_item}" /> </td>
{if $tiki_p_cc_admin eq 'y'}
<td><input type='text' name='from_id' size="24" value="{$smarty.request.from_id}" /></td>
{/if}
<td><input type='text' name='to_id' size="24" value="{$smarty.request.to_id}" /></td>
<td><input type='text' name='tr_amount' size="8" value="{$smarty.request.tr_amount}" /></td>
{if $currency}
<td><input type="text" name="cc_id" value="{$currency}" /></td>
{else}
<td><select name="cc_id" style="font-style:italic;">
<option value="">select cc</option>
{foreach key=ccid item=ccinfo from=$currencies}
<option value="{$ccid}" style="font-style:normal;"{if $ccid eq $smarty.request.cc_id} selected="selected"{/if}>{$ccid}</option>
{/foreach}
</select></td>
{/if}
</td>
</tr>


<tr class="formrow">
{if $tiki_p_cc_admin eq 'y' or $info.owner_id eq $user}
<td><select name='tr_type'>
<option type="submit" value='record'{if $info.tr_type eq 'record'} selected="selected"{/if}>{tr}record{/tr}</option>
<option type="submit" value='revert'{if $info.tr_type eq 'revert'} selected="selected"{/if}>{tr}revert{/tr}</option>
</select></td>
</tr><tr>
<td><input type='submit' value='confirm' /></td>
{else}
<td><input name='tr_type' type='submit' value='record' /></td>
{/if}
</tr>


</table>
</form>
