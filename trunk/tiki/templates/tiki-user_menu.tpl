{* $Header: /cvsroot/tikiwiki/tiki/templates/tiki-user_menu.tpl,v 1.25 2007-02-18 23:55:23 nyloth Exp $ *}
{assign var=opensec value='n'}
{assign var=sep value=''}

{if $menu_info.type eq 'e' or $menu_info.type eq 'd'}

{foreach key=pos item=chdata from=$channels}
{assign var=cname value=$menu_info.menuId|cat:'__'|cat:$chdata.position}
{if $chdata.type eq 's'}
{if $opensec eq 'y'}</div>{/if}
<div class="separator{$sep}">
{if $sep eq 'line'}{assign var=sep value=''}{/if}

{if $chdata.url}
{if $feature_menusfolderstyle eq 'y'}
<a class='separator' href="javascript:icntoggle('menu{$cname}');" title="{tr}Toggle options{/tr}"><img src="img/icons/{if $menu_info.type ne 'd'}o{/if}fo.gif" border="0" name="menu{$cname}icn" alt='{tr}Toggle{/tr}'/></a>
{else}<a class='separator' href="javascript:toggle('menu{$cname}');">[-]</a>{/if} 
<a href="{$chdata.url|escape}" class="separator">{tr}{$chdata.name}{/tr}</a>
{if $feature_menusfolderstyle ne 'y'}<a class='separator' href="javascript:toggle('menu{$cname}');">[+]</a>{/if} 
{else}
{if $feature_menusfolderstyle eq 'y'}
<a class='separator' href="javascript:icntoggle('menu{$cname}');" title="{tr}Toggle options{/tr}"><img src="img/icons/{if $menu_info.type ne 'd'}o{/if}fo.gif" border="0" name="menu{$cname}icn" alt='{tr}Toggle{/tr}'/>&nbsp;
{else}<a class='separator' href="javascript:toggle('menu{$cname}');">[-]{/if}{tr}{$chdata.name}{/tr}{if $feature_menusfolderstyle ne 'y'}[+]{/if}</a> 
{/if}
</div>
{assign var=opensec value='y'}
<div {if $menu_info.type eq 'd' and isset($smarty.session.tiki_cookie_jar.menu.$cname) and $smarty.session.tiki_cookie_jar.menu.$cname ne 'c'}style="display:none;"{else}style="display:block;"{/if} id='menu{$cname}'>
{elseif $chdata.type eq 'o'}
<div class="option{$sep}"><a href="{$chdata.url|escape}" class="linkmenu">{tr}{$chdata.name}{/tr}</a></div>
{if $sep eq 'line'}{assign var=sep value=''}{/if}
{else}
{if $chdata.type eq '-'}{if $opensec eq 'y'}</div>{/if}{assign var=opensec value='n'}{/if}
{assign var=sep value="line"}
{/if}
{/foreach}
{if $opensec eq 'y'}</div>{/if}

{else}
{foreach key=pos item=chdata from=$channels}
{if $chdata.type eq 's'}
<div class="separator{$sep}"><a class='separator' href="{$chdata.url|escape}">{tr}{$chdata.name}{/tr}</a></div>
{if $sep eq 'line'}{assign var=sep value=''}{/if}
{elseif $chdata.type eq 'o'}
<div class="option{$sep}"><a href="{$chdata.url|escape}" class="linkmenu">{tr}{$chdata.name}{/tr}</a></div>
{if $sep eq 'line'}{assign var=sep value=''}{/if}
{else}
{assign var=sep value='line'}
{/if}
{/foreach}
{/if}

{if $sep eq 'line'}
<div class="separator{$sep}">&nbsp;</div>
{/if}

{if $menu_info.type eq 'e' or $menu_info.type eq 'd'}
<script type='text/javascript'>
{foreach key=pos item=chdata from=$channels}
{if $chdata.type eq 's'}
  {if $feature_menusfolderstyle eq 'y'}
    setfolderstate('menu{$menu_info.menuId|cat:'__'|cat:$chdata.position}', '{$menu_info.type}');
  {else}
    setsectionstate('menu{$menu_info.menuId|cat:'__'|cat:$chdata.position}', '{$menu_info.type}');
  {/if}
{/if}
{/foreach}
</script>
{/if}

