<a class="pagetitle" href="tiki-pagehistory?page={$page}">{tr}History{/tr}</a> {tr}of{/tr}: <a class="pagetitle" href="tiki-index.php?page={$page}">{$page}</a><br/><br/>
{if $preview}
<h2>{tr}Version{/tr}: {$version}</h2>
<div  class="wikitext">{$preview.data}</div>
<br/> 
{/if}
{if $diff}
<h3>{tr}Comparing versions{/tr}</h3>
<table class="normalnoborder">
<tr>
  <td>{tr}Actual_version{/tr}</td>
  <td>{tr}Version{/tr}:{$version}</td>
</tr>
<tr>
  <td valign="top" width="50%"><div class="wikitext">{$parsed}</div></td>
  <td valign="top" width="50%"><div class="wikitext">{$diff}</div></td>
</tr>
</table>
{/if}
{if $diff2 eq 'y'}
<h3>{tr}Diff to version{/tr}: {$version}</h3>
{$diffdata}
{/if}
<br/>
<div align="center">
<table border="1" cellpadding="0" cellspacing="0">
<tr>
<td class="heading">{tr}Date{/tr}</td>
<td class="heading">{tr}Version{/tr}</td>
<td class="heading">{tr}User{/tr}</td>
<td class="heading">{tr}Ip{/tr}</td>
<td class="heading">{tr}Comment{/tr}</td>
<td class="heading">{tr}Action{/tr}</td>
</tr>
<tr>
<td class="odd">&nbsp;{$info.lastModif|tiki_short_datetime}&nbsp;</td>
<td class="odd">&nbsp;{$info.version}&nbsp;</td>
<td class="odd">&nbsp;{$info.user}&nbsp;</td>
<td class="odd">&nbsp;{$info.ip}&nbsp;</td>
<td class="odd">&nbsp;{$info.comment}&nbsp;</td>
<td class="odd">&nbsp;[{tr}current_version{/tr}]&nbsp;</td>
</tr>
{section name=hist loop=$history}
<tr>
{if $smarty.section.hist.index % 2}
<td class="odd">&nbsp;{$history[hist].lastModif|tiki_short_datetime}&nbsp;</td>
<td class="odd">&nbsp;{$history[hist].version}&nbsp;</td>
<td class="odd">&nbsp;{$history[hist].user}&nbsp;</td>
<td class="odd">&nbsp;{$history[hist].ip}&nbsp;</td>
<td class="odd">&nbsp;{$history[hist].comment}&nbsp;</td>
<td class="odd">&nbsp;<a class="link" href="tiki-pagehistory.php?page={$page}&amp;preview={$history[hist].version}">{tr}view{/tr}</a>&nbsp;
{if $tiki_p_remove eq 'y'}
<a class="link" href="tiki-removepage.php?page={$page}&amp;version={$history[hist].version}">{tr}remove{/tr}</a>&nbsp;
{/if}
{if $tiki_p_rollback eq 'y'}
<a class="link" href="tiki-rollback.php?page={$page}&amp;version={$history[hist].version}">{tr}rollback{/tr}</a>&nbsp;
{/if}
<a class="link" href="tiki-pagehistory.php?page={$page}&amp;diff={$history[hist].version}">{tr}compare{/tr}</a>&nbsp;
<a class="link" href="tiki-pagehistory.php?page={$page}&amp;diff2={$history[hist].version}">{tr}diff{/tr}</a>&nbsp;
</td>
{else}
<td class="even">&nbsp;{$history[hist].lastModif|tiki_short_datetime}&nbsp;</td>
<td class="even">&nbsp;{$history[hist].version}&nbsp;</td>
<td class="even">&nbsp;{$history[hist].user}&nbsp;</td>
<td class="even">&nbsp;{$history[hist].ip}&nbsp;</td>
<td class="even">&nbsp;{$history[hist].comment}&nbsp;</td>
<td class="even">&nbsp;<a class="link" href="tiki-pagehistory.php?page={$page}&amp;preview={$history[hist].version}">{tr}view{/tr}</a>&nbsp;
{if $tiki_p_remove eq 'y'}
<a class="link" href="tiki-removepage.php?page={$page}&amp;version={$history[hist].version}">{tr}remove{/tr}</a>&nbsp;
{/if}
{if $tiki_p_rollback eq 'y'}
<a class="link" href="tiki-rollback.php?page={$page}&amp;version={$history[hist].version}">{tr}rollback{/tr}</a>&nbsp;
{/if}
<a class="link" href="tiki-pagehistory.php?page={$page}&amp;diff={$history[hist].version}">{tr}compare{/tr}</a>&nbsp;
<a class="link" href="tiki-pagehistory.php?page={$page}&amp;diff2={$history[hist].version}">{tr}diff{/tr}</a>&nbsp;
</td>
{/if}
</tr>
{sectionelse}
<tr><td colspan="6">
<b>{tr}No records found{/tr}</b>
</td></tr>
{/section}
</table>
</div>
